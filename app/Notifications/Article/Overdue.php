<?php

namespace App\Notifications\Article;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Overdue extends Notification
{
    use Queueable;

    protected $article;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Article $article)
    {
        //notify Manager
        $this->article = $article;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
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
            ->subject(_i('Low rating'))
            ->line(_i('Hello %s', [$notifiable->name]))
            ->line(_i('Article "%s" is overdue.', [$this->article->title]))
            ->line(_i('Please, contact to client.'))
            ->action('Review article', action('Project\ArticlesController@show', [$this->article->project, $this->article]))
            ->line('Thank you for using our application!');
    }

}
