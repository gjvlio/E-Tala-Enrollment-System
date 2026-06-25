<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Symfony\Component\Mime\Email;

class ApplicationReturnedNotification extends Notification
{
    public string $remarks;

    public function __construct(string $remarks)
    {
        $this->remarks = $remarks;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Action needed on your application — '.config('school.short'))
            ->markdown('emails.returned', [
                'remarks' => $this->remarks,
                'fixUrl' => route('application.show'),
            ])
            ->withSymfonyMessage(function (Email $message) {
                $message->embedFromPath(public_path('images/logo.png'), 'logo');
            });
    }
}
