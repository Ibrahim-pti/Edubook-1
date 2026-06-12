<?php

namespace App\Filament\Pages\Auth;

use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Components\Component;
use Illuminate\Support\Facades\Auth;

class CustomLogin extends BaseLogin
{
    protected static string $view = 'filament.pages.auth.custom-login';

    public function getHeading(): string | Htmlable
    {
        return 'چوونە ژوورەوە';
    }

    public function getSubmitButtonLabel(): string | Htmlable
    {
        return 'چوونە ژوورەوە';
    }

    protected function getEmailFormComponent(): Component
    {
        return parent::getEmailFormComponent()
            ->label('ئیمەیڵ');
    }

    protected function getPasswordFormComponent(): Component
    {
        return parent::getPasswordFormComponent()
            ->label('وشەی نهێنی');
    }

    protected function getRememberFormComponent(): Component
    {
        return parent::getRememberFormComponent()
            ->label('من بەبیر بهێنەرەوە');
    }

    /**
     * هاوکات لە گارڈی وێبیشەوە لۆگین بکە تاکو پۆرتالیش بناسێتی
     * و دووجار لۆگین پێویست نەبێت
     */
    public function authenticate(): ?LoginResponse
    {
        $response = parent::authenticate();

        if ($response !== null && Filament::auth()->check()) {
            Auth::guard('web')->login(Filament::auth()->user());
        }

        return $response;
    }
}
