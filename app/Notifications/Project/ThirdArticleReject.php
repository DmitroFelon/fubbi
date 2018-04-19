<?php

namespace App\Notifications\Project;

use App\Models\Article;
use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ThirdArticleReject extends Notification
{
    use Queueable;

    protected $article;

    /**
     * Create a new notification instance.
     *
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
        return ['mail'];
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
            ->subject(_i('Article has been rejected 3 times'))
            ->line(_i('Hello %s', [$notifiable->name]))
            ->line(_i('Article %s has been rejected 3 times', [$this->article->title]))
            ->line(_i('Please contact to article author: %s.', [$this->article->author->name]))
            ->line(_i('Phone: %s', [$this->article->author->phone]))
            ->line(_i('Email: %s', [$this->article->author->email]))
            ->action('Review article', action('Project\ArticlesController@show', [$this->article->project, $this->article]))
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
        return [
            //
        ];
    }
}
