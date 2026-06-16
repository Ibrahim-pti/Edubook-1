<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AdminMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class NotificationController extends Controller
{
    public function index()
    {
        return view('admin.notifications.index');
    }

    public function broadcast(Request $request)
    {
        $data = $request->validate([
            'title'   => 'required|string|max:255',
            'message' => 'required|string',
            'image'   => 'nullable|file|image|max:5120',
        ]);

        $imageUrl = null;

        if ($request->hasFile('image')) {
            $file     = $request->file('image');
            $filename = 'notification-images/' . uniqid() . '.webp';

            $compressed = Image::read($file)
                ->scale(width: 800)
                ->toWebp(80);

            Storage::disk('public')->put($filename, (string) $compressed);

            $imageUrl = url('storage/' . $filename);
        }

        $users  = User::where('notifications_enabled', true)->whereNotNull('fcm_token')->get();
        $tokens = $users->pluck('fcm_token')->filter()->unique()->values()->toArray();
        $count  = count($tokens);

        // Save to DB for all users with notifications enabled
        foreach ($users as $user) {
            $user->notify(new AdminMessage($data['title'], $data['message']));
        }

        if ($count === 0) {
            return back()->with('warning', 'هیچ بەکارهێنەرێک تۆکنی مۆبایلی چالاک نییە.');
        }

        $firebase  = app(\App\Services\FirebaseNotificationService::class);
        $result    = $firebase->sendToMultipleTokens($tokens, $data['title'], $data['message'], [], $imageUrl);
        $successful = $result['successful'] ?? 0;

        if ($successful === 0) {
            return back()->with('warning', "پەیامەکە لە داتابەیس تۆمارکرا، بەڵام هیچ Push نەگەیشت ({$count} تۆکن نادروست بوون).");
        } elseif ($successful < $count) {
            return back()->with('info', "بڵاوکرایەوە بۆ {$successful} لە {$count} مۆبایل.");
        }

        return back()->with('success', "بە سەرکەوتوویی نێردرا بۆ {$successful} مۆبایل ✅");
    }
}
