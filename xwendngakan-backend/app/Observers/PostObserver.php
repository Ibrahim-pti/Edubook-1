<?php

namespace App\Observers;

use App\Models\Post;
use App\Models\User;
use App\Notifications\AdminMessage;
use App\Services\FirebaseNotificationService;

class PostObserver
{
    /**
     * Notify users when a new post is published.
     */
    public function created(Post $post): void
    {
        if ($post->approved) {
            $this->notifyNewPost($post);
        }
    }

    /**
     * Notify users when a pending post becomes approved (published later).
     */
    public function updated(Post $post): void
    {
        if ($post->wasChanged('approved') && $post->approved) {
            $this->notifyNewPost($post);
        }
    }

    /**
     * Push + in-app notification for a freshly published post.
     * Carries a `post` data payload so tapping the notification opens the post.
     */
    protected function notifyNewPost(Post $post): void
    {
        $post->loadMissing('institution');
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

        $users = User::where('notifications_enabled', true)->get();
        if ($users->isEmpty()) {
            return;
        }

        // In-app notification (database) for everyone — feeds the list + badge.
        foreach ($users as $user) {
            $user->notify(new AdminMessage($title, $body, $data));
        }

        // Push only to users with a device token (dedupe handled by the service).
        $tokens = $users->pluck('fcm_token')->filter()->all();
        if (! empty($tokens)) {
            app(FirebaseNotificationService::class)
                ->sendToMultipleTokens($tokens, $title, $body, $data);
        }
    }
}
