<?php

namespace App\Notifications\Project;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StatusChanged extends Notification
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
            ->subject('Project status changed')
            ->line(_i('Hello %s', [$notifiable->first_name]))
            ->line(_i('%s status has been changed to: %s', [
                $this->project->name,
                ucfirst(str_replace('_', ' ', $this->project->state))
            ]))
            ->action('Review project', url()->action('Resources\ProjectController@show', $this->project))
            ->line('Thank you for using our application!');
    }

}
