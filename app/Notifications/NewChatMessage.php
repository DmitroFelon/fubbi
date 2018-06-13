<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Class NewChatMessage
 * @package App\Notifications
 */
class NewChatMessage extends Notification
{
    /**
     * @var
     */
    protected $user;

    /**
     * @var
     */
    protected $conversation_id;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $conversation_id)
    {
        $this->user = $user;
        $this->conversation_id = $conversation_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('You\'ve received the new message.')
            ->action('To conversation', url()->action('Resources\MessageController@index') . '?c=' . $this->conversation_id)
            ->line('Thank you for using our application!');
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
                'You\'ve received the new message.',
                [$this->user->name]
            ),
            url()->action('Resources\MessageController@index') . '?c=' . $this->conversation_id,
            get_class($this->user),
            $this->user->id
        );
        return $notification->toArray();
    }
}
