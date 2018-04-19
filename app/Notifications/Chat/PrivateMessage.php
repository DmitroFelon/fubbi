<?php

namespace App\Notifications\Chat;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Musonza\Chat\Conversations\Conversation;
use Musonza\Chat\Messages\Message;

class PrivateMessage extends Notification
{
    use Queueable;

    protected $message;

    /**
     * Create a new notification instance.
     * @param Conversation $conversation
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via(User $notifiable)
    {
        return ($notifiable->disabled_notifications()->where('name', get_class($this))->get())
            ? [] : ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(_i('New Message'))
            ->line(_i('Hello %s', [$notifiable->name]))
            ->line(_i('You have a new message from %s.', [$this->message->sender->name]))
            ->action('Open conversation', action('Resources\MessageController@index', ['c' => $this->message->conversation->id]))
            ->line('Thank you for using our application!');
    }

}
