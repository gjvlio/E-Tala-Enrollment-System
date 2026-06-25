<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Mailer\Bridge\Sendgrid\Transport\SendgridApiTransport;
use Symfony\Component\Mime\Email;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Mail::extend('sendgrid', function () {
            return new SendgridApiTransport((string) config('services.sendgrid.key'));
        });

        Paginator::useBootstrapFive();

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verify your email — '.config('school.short'))
                ->markdown('emails.verify', ['url' => $url])
                ->withSymfonyMessage(function (Email $message) {
                    $message->embedFromPath(public_path('images/logo.png'), 'logo');
                });
        });
    }
}
