<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountCompletionNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $bio;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $bio)
    {
        $this->user = $user;
        $this->bio = $bio;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Generate Secure Signed URL for Auto Login
        $targetUrl = url('/users?search=' . $this->user->id);
        $signedUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'auto-login', 
            now()->addDays(7), // Link valid for 7 days
            ['admin_id' => $notifiable->id, 'redirect' => $targetUrl]
        );

        return (new MailMessage)
                    ->subject('🔔 Persetujuan Akun Baru: ' . $this->user->name)
                    ->view('emails.account_completion', [
                        'user' => $this->user,
                        'bio'  => $this->bio,
                        'actionUrl' => $signedUrl
                    ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->user->id,
            'name' => $this->user->name,
            'message' => 'Telah melengkapi profil: ' . $this->bio,
        ];
    }
}
