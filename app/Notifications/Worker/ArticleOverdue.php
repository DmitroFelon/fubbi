<?php

namespace App\Notifications\Worker;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ArticleOverdue extends Notification
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
        return [];
        return ($notifiable->disabled_notifications()->where('name', get_class($this))->first())
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
            ->subject(_i('Article Overdue'))
            ->line(_i('Hello %s', [$notifiable->name]))
            ->line(_i('Article %s is overdue.', [$this->article->title]))
            ->action('Review article', action('Project\ArticlesController@show', [
                $this->article->project,
                $this->article
            ]))
            ->line('Thank you for using our application!');
    }

}
