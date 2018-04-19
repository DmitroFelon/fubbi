<?php

namespace App\Notifications\Article;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Approval extends Notification
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
        //notify client
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
        $result = $notifiable->disabled_notifications()->where('name', get_class($this))->first();
        $is     = ($result) ? [] : ['mail'];
        return $is;
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
            ->subject(_i('Content approval'))
            ->line(_i('Hello %s', [$notifiable->first_name]))
            ->line(_i('You have content ready for approval.'))
            ->action('Review', action('Project\ArticlesController@show', [
                $this->article->project,
                $this->article
            ]));
    }

}
