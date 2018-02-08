<?php

namespace App\Notifications\Manager;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InviteOverdue extends Notification
{
    use Queueable;

    protected $project;

    /**
     * Create a new notification instance.
     *
     * @param Project $project
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
            ->subject(_i('Invite Overdue'))
            ->line(_i('Hello %s', [$notifiable->name]))
            ->line(_i('Project "%s" requires workers: ', [
                $this->project->name,
                implode(', ', $this->project->requireWorkers())
            ]))
            ->action('Review project', action('Resources\ProjectController@show', [
                $this->project
            ]))
            ->line('Thank you for using our application!');
    }

}
