<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use App\Models\User;
use App\Notifications\AdminMessage;
use Filament\Notifications\Notification;

class SendNotifications extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.pages.send-notifications';

    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';

    protected static ?string $navigationLabel = 'ناردنی ئاگادارکردنەوە';

    protected static ?string $navigationGroup = 'سیستەم';

    protected static ?int $navigationSort = 15;

    public ?array $data = [];

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\Section::make('بۆ هەموو بەکارهێنەر')
                    ->icon('heroicon-o-megaphone')
                    ->schema([
                                        Forms\Components\TextInput::make('broadcast_title')
                                            ->label('ناونیشان')
                                            ->placeholder('بۆ نموونە: بەڕێوەبەری سیستەم')
                                            ->columnSpanFull(),

                                        Forms\Components\Textarea::make('broadcast_body')
                                            ->label('پەیام')
                                            ->placeholder('پەیامی ئاگادارکردنەوەی خۆت بنووسە')
                                            ->rows(4)
                                            ->columnSpanFull(),

                                        Forms\Components\Actions::make([
                                            Forms\Components\Actions\Action::make('sendBroadcast')
                                                ->label('بلاوکردنەوە')
                                                ->icon('heroicon-o-paper-airplane')
                                                ->color('success')
                                                ->action(function (): void {
                                                    $data = $this->form->getState();

                                                    if (empty($data['broadcast_title']) || empty($data['broadcast_body'])) {
                                                        Notification::make()
                                                            ->title('ناونیشان و پەیام پێویست دەکەن')
                                                            ->danger()
                                                            ->send();
                                                        return;
                                                    }

                                                    $customData = [];
                                                    if (!empty($data['broadcast_data'])) {
                                                        try {
                                                            $customData = json_decode($data['broadcast_data'], true) ?? [];
                                                        } catch (\Exception $e) {
                                                            Notification::make()
                                                                ->title('فۆرمایتی JSON غەلەیە')
                                                                ->danger()
                                                                ->send();
                                                            return;
                                                        }
                                                    }

                                                    $users = User::where('notifications_enabled', true)
                                                        ->whereNotNull('fcm_token')
                                                        ->get();
                                                    // Dedupe: the same device token can sit on several
                                                    // accounts — send once per device, not once per account.
                                                    $tokens = $users->pluck('fcm_token')->filter()->unique()->values()->toArray();
                                                    $count  = count($tokens);

                                                    if ($count === 0) {
                                                        Notification::make()
                                                            ->title('هیچ بەکارهێنەرێک تۆکنی مۆبایلی چالاک نییە')
                                                            ->body('بۆ ئەوەی بڵاوکردنەوە بگات، پێویستە بەکارهێنەران ئاپەکە لەسەر مۆبایل بکەنەوە و بچنە ژوورەوە تا تۆکنی FCM یان تۆمار بکرێت.')
                                                            ->warning()
                                                            ->send();
                                                        return;
                                                    }

                                                    // Save to DB for all users
                                                    foreach ($users as $user) {
                                                        $user->notify(new AdminMessage(
                                                            $data['broadcast_title'],
                                                            $data['broadcast_body'],
                                                            $customData
                                                        ));
                                                    }

                                                    // Send push via FCM batch
                                                    $firebase = app(\App\Services\FirebaseNotificationService::class);
                                                    $result   = $firebase->sendToMultipleTokens(
                                                        $tokens,
                                                        $data['broadcast_title'],
                                                        $data['broadcast_body'],
                                                        $customData
                                                    );

                                                    $successful = $result['successful'];

                                                    if ($successful === 0) {
                                                        // All tokens failed — usually stale/expired and now auto-removed
                                                        Notification::make()
                                                            ->title('Push نەگەیشت')
                                                            ->body("پەیامەکە لە داتابەیس تۆمارکرا، بەڵام هیچ کام لە {$count} تۆکنەکە کارا نەبوون (لەوانەیە کۆن/نادروست بن و سڕانەوە). داوا لە بەکارهێنەران بکە ئاپەکە بکەنەوە.")
                                                            ->danger()
                                                            ->persistent()
                                                            ->send();
                                                    } elseif ($successful < $count) {
                                                        Notification::make()
                                                            ->title('بڵاوکردنەوەی بەشەکی')
                                                            ->body("نێردرا بۆ {$successful} لە {$count} مۆبایل. ئەوانی تر تۆکنی نادروستیان هەبوو و سڕانەوە.")
                                                            ->warning()
                                                            ->send();
                                                    } else {
                                                        Notification::make()
                                                            ->title('بڵاوکردنەوەی سەرکەوتوو')
                                                            ->body("نێردرا بۆ هەموو {$successful} مۆبایل ✅")
                                                            ->success()
                                                            ->send();
                                                    }
                                                }),
                                        ])
                                            ->columnSpanFull()
                                            ->alignment('center'),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
