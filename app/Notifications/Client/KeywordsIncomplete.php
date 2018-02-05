<?php

namespace App\Notifications\Client;

use App\Models\Helpers\ProjectStates;
use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KeywordsIncomplete extends Notification
{
    use Queueable;

    protected $project;

    /**
     * Create a new notification instance.
     *
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ($notifiable->disabledNotifications()->where('name', get_class($this))->get())
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
            ->subject(_i('Keywords incomplete'))
            ->line(_i('Hello %s', [$notifiable->name]))
            ->line(_i('Please complete keywords filling'))
            ->action('Complete', action('ProjectController@edit', [
                $this->project,
                's' => ProjectStates::KEYWORDS_FILLING
            ]))
            ->line('Thank you for using our application!');
    }

}
