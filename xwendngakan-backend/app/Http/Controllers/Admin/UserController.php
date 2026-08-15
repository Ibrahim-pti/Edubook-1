<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Institution;
use App\Notifications\AdminMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->where('is_admin', false);

        if ($search = $request->search) {
            $query->where(fn($q) => $q->where('name','like',"%$search%")
                                       ->orWhere('email','like',"%$search%")
                                       ->orWhere('phone','like',"%$search%"));
        }
        if ($request->approved !== null && $request->approved !== '') {
            $query->where('is_approved', (bool)$request->approved);
        }
        if ($request->user_type) {
            $query->where('user_type', $request->user_type);
        }

        $users = $query->latest()->paginate(25)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function toggleApproval(Request $request, User $user)
    {
        $newState = !$user->is_approved;
        $user->update(['is_approved' => $newState]);

        // If activating and institution_id provided, link
        if ($newState && $request->institution_id) {
            Institution::where('id', $request->institution_id)
                ->update(['user_id' => $user->id]);
        }

        $msg = $newState ? 'هەژمارەکە چالاككرا.' : 'هەژمارەکە ناچالاككرا.';
        return back()->with('success', $msg);
    }

    public function sendNotification(Request $request, User $user)
    {
        $data = $request->validate([
            'title'   => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $user->notify(new AdminMessage($data['title'], $data['message']));

        if ($user->fcm_token && $user->notifications_enabled) {
            $firebase = app(\App\Services\FirebaseNotificationService::class);
            $firebase->sendToToken($user->fcm_token, $data['title'], $data['message']);
        }

        return back()->with('success', 'نۆتیفیکەیشنەکە بە سەرکەوتوویی نێردرا.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'بەکارهێنەرەکە سڕایەوە.');
    }
}
