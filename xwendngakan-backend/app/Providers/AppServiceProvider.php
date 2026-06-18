<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;
use App\Services\FirebaseNotificationService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Firebase
        $this->registerFirebase();

        // Register Firebase Notification Service
        $this->app->singleton(FirebaseNotificationService::class, function ($app) {
            return new FirebaseNotificationService($app['firebase.messaging']);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production, or whenever the proxy says the original request was HTTPS.
        // APP_ENV=production must be set on the server — also covers Cloudflare's forwarded header.
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
            \Illuminate\Support\Facades\URL::forceRootUrl(config('app.url'));
        }

        // Notify users when a post is published (covers both API and admin panel)
        \App\Models\Post::observe(\App\Observers\PostObserver::class);

        // جیاکردنەوەی کوکی سێشن بۆ ئەدمین و پۆرتال + دیاریکردنی زمانی کوردی بۆ ئەدمین
        if (request()->is('admin*') || (request()->is('livewire*') && str_contains(request()->header('referer', ''), '/admin'))) {
            config(['session.cookie' => env('ADMIN_SESSION_COOKIE', 'edubook_admin_session')]);
            app()->setLocale('ku');
        }
    }

    /**
     * Register Firebase instance.
     */
    protected function registerFirebase(): void
    {
        $this->app->singleton('firebase', function ($app) {
            $credentialsPath = config('firebase.credentials');

            if (!$credentialsPath || !file_exists($credentialsPath)) {
                \Illuminate\Support\Facades\Log::warning(
                    "Firebase credentials file not found at: {$credentialsPath}. Firebase features are disabled."
                );
                return null;
            }

            return (new Factory())->withServiceAccount($credentialsPath);
        });

        $this->app->singleton('firebase.messaging', function ($app) {
            $firebase = $app['firebase'];
            return $firebase ? $firebase->createMessaging() : null;
        });

        $this->app->singleton('firebase.auth', function ($app) {
            $firebase = $app['firebase'];
            return $firebase ? $firebase->createAuth() : null;
        });
    }
}
