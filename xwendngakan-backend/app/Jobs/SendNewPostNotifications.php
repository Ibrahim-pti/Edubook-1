<?php

namespace App\Jobs;

use App\Models\Post;
use App\Models\User;
use App\Notifications\AdminMessage;
use App\Services\FirebaseNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Fan-out a "new post" notification to all users in the background.
 *
 * This used to run inline inside the web request (PostObserver), which made
 * post creation block for tens of seconds while it wrote a DB notification per
 * user and called Firebase for every token — exhausting PHP-FPM workers. Now
 * it runs on the queue so the request returns immediately.
 */
class SendNewPostNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;

    public function __construct(public int $postId)
    {
    }

    public function handle(FirebaseNotificationService $firebase): void
    {
        $post = Post::with('institution')->find($this->postId);
        if (! $post || ! $post->approved) {
            return;
        }

        $institutionName = $post->institution->nku
            ?? $post->institution->nen
            ?? 'دامەزراوەکە';

        $title = 'پۆستێکی نوێ بڵاوکرایەوە';
        $body  = "{$institutionName} بابەتێکی نوێی بڵاوکردەوە: " . ($post->title ?: 'پۆستێکی نوێ');
        $data  = [
            'type'           => 'post',
            'post_id'        => (string) $post->id,
            'institution_id' => (string) $post->institution_id,
        ];

        // In-app notification (database) for everyone — feeds the list + badge.
        // Chunked so we never load every user into memory at once.
        User::where('notifications_enabled', true)
            ->chunkById(500, function ($users) use ($title, $body, $data) {
                foreach ($users as $user) {
                    $user->notify(new AdminMessage($title, $body, $data));
                }
            });

        // Push only to users with a device token (dedupe + batching in the service).
        $tokens = User::where('notifications_enabled', true)
            ->whereNotNull('fcm_token')
            ->pluck('fcm_token')
            ->all();

        if (! empty($tokens)) {
            $firebase->sendToMultipleTokens($tokens, $title, $body, $data);
        }
    }
}
