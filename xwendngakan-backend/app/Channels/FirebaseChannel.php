<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Services\FirebaseNotificationService;

class FirebaseChannel
{
    protected $firebaseService;

    public function __construct(FirebaseNotificationService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        if (!method_exists($notification, 'toFirebase')) {
            return;
        }

        $message = $notification->toFirebase($notifiable);

        if (!$message) {
            return;
        }

        // Check if the notifiable model has an fcm_token
        if (isset($notifiable->fcm_token) && !empty($notifiable->fcm_token)) {
            $this->firebaseService->sendToToken(
                $notifiable->fcm_token,
                $message['title'] ?? '',
                $message['body'] ?? '',
                $message['data'] ?? []
            );
        }
    }
}
