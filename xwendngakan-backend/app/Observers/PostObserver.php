<?php

namespace App\Observers;

use App\Jobs\SendNewPostNotifications;
use App\Models\Post;

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
     * Queue the new-post fan-out so the web request returns immediately.
     * The heavy work (a DB notification per user + Firebase push to every
     * token) runs in the background via SendNewPostNotifications.
     */
    protected function notifyNewPost(Post $post): void
    {
        SendNewPostNotifications::dispatch($post->id);
    }
}
