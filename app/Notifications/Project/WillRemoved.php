<?php

namespace App\Notifications\Project;

use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WillRemoved extends Notification
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
        //todo notify client ebout ending of subscription
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
            ->subject(_i('Project will be removed!'))
            ->line(_i('Hello %s', [$notifiable->name]))
            ->line(_i('Project %s will be removed at: %s.', [
                $this->project->name,
                ($this->project->subscription->ends_at)
                    ? $this->project->subscription->ends_at->format('m.d.y h:i:s')
                    : Carbon::now()->format('m.d.y h:i:s')
            ]))
            ->action('Review project', action('Resources\ProjectController@show', $this->project))
            ->line('Thank you for using our application!');
    }


}
