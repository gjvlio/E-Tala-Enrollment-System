<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Render (and most PaaS) terminate TLS at a proxy, so the app sees http
        // and emits http asset URLs that browsers block on the https page. Force
        // https in production regardless of APP_URL/proxy headers.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Bootstrap 5 pagination (Tailwind was removed) so prev/next arrows render sized.
        Paginator::useBootstrapFive();

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verify your email — '.config('school.short'))
                ->markdown('emails.verify', ['url' => $url])
                ->withSymfonyMessage(function (\Symfony\Component\Mime\Email $message) {
                    $message->embedFromPath(public_path('images/logo.png'), 'logo');
                });
        });
    }
}
