<?php

namespace App\Notifications\Client;

use App\Models\Helpers\ProjectStates;
use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuizIncomplete extends Notification
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
            ->subject(_i('Quiz incomplete'))
            ->line(_i('Hello %s', [$notifiable->name]))
            ->line(_i('Please complete quiz filling'))
            ->action('Complete', action('Resources\ProjectController@edit', [
                $this->project,
                's' => ProjectStates::QUIZ_FILLING
            ]))
            ->line('Thank you for using our application!');
    }
}
