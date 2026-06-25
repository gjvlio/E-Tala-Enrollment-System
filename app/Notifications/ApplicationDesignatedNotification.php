<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationDesignatedNotification extends Notification
{
    public string $schoolId;
    public string $defaultPassword;
    public string $section;

    public function __construct(string $schoolId, string $defaultPassword, string $section)
    {
        $this->schoolId = $schoolId;
        $this->defaultPassword = $defaultPassword;
        $this->section = $section;
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('A slot opened for you — '.config('school.short'))
            ->markdown('emails.designated', [
                'schoolId' => $this->schoolId,
                'password' => $this->defaultPassword,
                'section'  => $this->section,
                'loginUrl' => route('login'),
            ])
            ->withSymfonyMessage(function (\Symfony\Component\Mime\Email $message) {
                $message->embedFromPath(public_path('images/logo.png'), 'logo');
            });
    }
}
