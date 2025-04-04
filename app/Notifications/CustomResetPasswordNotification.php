<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPasswordNotification extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $apiResetUrl = url("/api/password/reset") . "?token={$this->token}&email={$notifiable->email}";

        return (new MailMessage)
            ->subject('Reset Your Password')
            ->greeting('Hello!')
            ->line('You requested a password reset. Use the token below to reset your password.')
            ->line("Token: {$this->token}")
            ->line("Email: {$notifiable->email}")
            ->line('Send a POST request to the API endpoint to reset your password.')
            ->line('If you did not request this, please ignore this email.');
    }
}
