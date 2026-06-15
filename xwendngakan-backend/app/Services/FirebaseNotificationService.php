<?php

namespace App\Services;

use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\TopicManagementError;
use Kreait\Firebase\Messaging\MulticastSendReport;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class FirebaseNotificationService
{
    protected ?Messaging $messaging;

    public function __construct(?Messaging $messaging = null)
    {
        $this->messaging = $messaging;
    }

    /**
     * Build a CloudMessage array — FCM v1 compatible fields only.
     */
    private function buildMessage(array $target, string $title, string $body, array $data = []): array
    {
        return array_merge($target, [
            'notification' => [
                'title' => $title,
                'body'  => $body,
            ],
            'data' => array_map('strval', array_merge($data, [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'title'        => $title,
                'body'         => $body,
            ])),
            'android' => [
                'priority'     => 'high',
                'notification' => [
                    'channel_id'              => 'high_importance_channel',
                    'notification_priority'   => 'PRIORITY_HIGH',
                    'sound'                   => 'default',
                    'default_vibrate_timings' => true,
                ],
            ],
            'apns' => [
                'headers' => [
                    'apns-priority' => '10',
                ],
                'payload' => [
                    'aps' => [
                        'alert' => [
                            'title' => $title,
                            'body'  => $body,
                        ],
                        'sound' => 'default',
                        'badge' => 1,
                    ],
                ],
            ],
        ]);
    }

    /**
     * Send notification to a single device token.
     * If the token is invalid/expired it is automatically cleared from DB.
     *
     * @param string $token FCM token
     * @param string $title Notification title
     * @param string $body  Notification body
     * @param array  $data  Additional data payload
     * @return bool Success status
     */
    public function sendToToken(
        string $token,
        string $title,
        string $body,
        array $data = []
    ): bool {
        if (!$this->messaging) {
            Log::warning('Firebase messaging is disabled (no credentials).');
            return false;
        }

        try {
            $message = CloudMessage::fromArray(
                $this->buildMessage(['token' => $token], $title, $body, $data)
            );

            $this->messaging->send($message);
            return true;
        } catch (\Exception $e) {
            $error = $e->getMessage();
            Log::error('Firebase Send Error: ' . $error);

            // Auto-remove invalid / expired tokens from DB
            if (
                str_contains($error, 'Requested entity was not found') ||
                str_contains($error, 'not a valid FCM registration token') ||
                str_contains($error, 'registration-token-not-registered') ||
                str_contains($error, 'invalid-registration-token')
            ) {
                $this->clearInvalidToken($token);
            }

            return false;
        }
    }

    /**
     * Send notification to multiple tokens using FCM batch API.
     * Invalid tokens are automatically cleared from DB.
     *
     * @param array  $tokens Array of FCM tokens
     * @param string $title  Notification title
     * @param string $body   Notification body
     * @param array  $data   Additional data payload
     * @return array Result with success count and failed tokens
     */
    public function sendToMultipleTokens(
        array $tokens,
        string $title,
        string $body,
        array $data = []
    ): array {
        if (!$this->messaging) {
            Log::warning('Firebase messaging is disabled (no credentials).');
            return ['successful' => 0, 'failed' => $tokens, 'total' => count($tokens)];
        }

        $tokens = array_values(array_filter($tokens));
        if (empty($tokens)) {
            return ['successful' => 0, 'failed' => [], 'total' => 0];
        }

        $successful = 0;
        $failed     = [];

        // FCM multicast supports up to 500 tokens per batch
        foreach (array_chunk($tokens, 500) as $chunk) {
            try {
                $messages = array_map(
                    fn($t) => CloudMessage::fromArray(
                        $this->buildMessage(['token' => $t], $title, $body, $data)
                    ),
                    $chunk
                );

                /** @var MulticastSendReport $report */
                $report = $this->messaging->sendAll($messages);

                foreach ($report->successes()->getItems() as $success) {
                    $successful++;
                }

                foreach ($report->failures()->getItems() as $failure) {
                    $failedToken = $chunk[$failure->messageIndex()] ?? null;
                    if ($failedToken) {
                        $failed[] = $failedToken;
                        $error    = $failure->error()?->getMessage() ?? '';
                        if (
                            str_contains($error, 'Requested entity was not found') ||
                            str_contains($error, 'not a valid FCM registration token') ||
                            str_contains($error, 'registration-token-not-registered') ||
                            str_contains($error, 'invalid-registration-token')
                        ) {
                            $this->clearInvalidToken($failedToken);
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('Firebase Batch Send Error: ' . $e->getMessage());
                // Fall back to one-by-one for this chunk
                foreach ($chunk as $token) {
                    if ($this->sendToToken($token, $title, $body, $data)) {
                        $successful++;
                    } else {
                        $failed[] = $token;
                    }
                }
            }
        }

        return [
            'successful' => $successful,
            'failed'     => $failed,
            'total'      => count($tokens),
        ];
    }

    /**
     * Send notification to a topic.
     *
     * @param string $topic Topic name
     * @param string $title Notification title
     * @param string $body  Notification body
     * @param array  $data  Additional data payload
     * @return bool Success status
     */
    public function sendToTopic(
        string $topic,
        string $title,
        string $body,
        array $data = []
    ): bool {
        if (!$this->messaging) {
            Log::warning('Firebase messaging is disabled (no credentials).');
            return false;
        }

        try {
            $message = CloudMessage::fromArray(
                $this->buildMessage(['topic' => $topic], $title, $body, $data)
            );

            $this->messaging->send($message);
            return true;
        } catch (\Exception $e) {
            Log::error('Firebase Topic Send Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Subscribe tokens to a topic.
     *
     * @param string $topic  Topic name
     * @param array  $tokens Array of FCM tokens
     * @return bool Success status
     */
    public function subscribeToTopic(string $topic, array $tokens): bool
    {
        if (!$this->messaging) {
            Log::warning('Firebase messaging is disabled (no credentials).');
            return false;
        }

        try {
            $this->messaging->subscribeToTopic($topic, ...$tokens);
            return true;
        } catch (TopicManagementError $e) {
            Log::error('Firebase Subscribe Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Unsubscribe tokens from a topic.
     *
     * @param string $topic  Topic name
     * @param array  $tokens Array of FCM tokens
     * @return bool Success status
     */
    public function unsubscribeFromTopic(string $topic, array $tokens): bool
    {
        if (!$this->messaging) {
            Log::warning('Firebase messaging is disabled (no credentials).');
            return false;
        }

        try {
            $this->messaging->unsubscribeFromTopic($topic, ...$tokens);
            return true;
        } catch (TopicManagementError $e) {
            Log::error('Firebase Unsubscribe Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Remove an invalid/expired FCM token from all users in the DB.
     */
    private function clearInvalidToken(string $token): void
    {
        try {
            $updated = User::where('fcm_token', $token)->update(['fcm_token' => null]);
            if ($updated) {
                Log::info('Cleared invalid FCM token from DB: ' . substr($token, 0, 20) . '...');
            }
        } catch (\Exception $e) {
            Log::warning('Could not clear invalid FCM token: ' . $e->getMessage());
        }
    }
}
