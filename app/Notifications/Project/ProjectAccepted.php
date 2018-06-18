<?php

namespace App\Notifications\Project;

use App\Notifications\NotificationPayload;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ProjectAccepted extends Notification
{
    use Queueable;

    protected $project;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($project)
    {
        $this->project = $project;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Project accepted!')
            ->line(_i('Hello %s', [$notifiable->first_name]))
            ->line(_i('Project "%s" has been accepted by manager.', [$this->project->name]))
            ->action('To project', url()->action('Resources\ProjectController@show', ['project' => $this->project->id]))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $notification = NotificationPayload::make(
            _i(
                'Project "%s" has been accepted by manager.',
                [$this->project->name]
            ),
            url()->action('Resources\ProjectController@show', ['project' => $this->project->id]),
            get_class($this->project),
            $this->project->id
        );
        return $notification->toArray();
    }
}
