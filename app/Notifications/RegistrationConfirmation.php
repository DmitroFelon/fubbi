<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Cache;

class RegistrationConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;

    /**
     * Create a new notification instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject('Set up your account')
            ->line('Hi, ' . $this->user->first_name);

        if ($this->user->tmp_password) {
            $message->line('Your password is: ' . $this->user->tmp_password);
        }

        $message
            ->line('Please set up your account. ')
            ->action('See your profile', url('/settings/'));

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $notification = NotificationPayload::make(
            _i(
                'Thank You for registration.',
                [$this->user->name]
            ),
            url()->action('Resources\UserController@show', $this->user),
            get_class($this->user),
            $this->user->id
        );

        return $notification->toArray();
    }
}
