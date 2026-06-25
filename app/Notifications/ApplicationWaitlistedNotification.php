<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Symfony\Component\Mime\Email;

class ApplicationWaitlistedNotification extends Notification
{
    public string $strand;

    public function __construct(string $strand)
    {
        $this->strand = $strand;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your application is waitlisted — '.config('school.short'))
            ->markdown('emails.waitlisted', ['strand' => $this->strand])
            ->withSymfonyMessage(function (Email $message) {
                $message->embedFromPath(public_path('images/logo.png'), 'logo');
            });
    }
}
