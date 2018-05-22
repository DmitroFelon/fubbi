<?php

namespace App\Notifications\Worker;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Class Attached
 * @package App\Notifications\Worker
 */
class Attached extends Notification
{
    use Queueable;

    /**
     * @var Project
     */
    protected $project;
    protected $role;


    /**
     * Create a new notification instance.
     *
     * @param Project $project
     */
    public function __construct(Project $project, $role)
    {
        $this->project = $project;
        $this->role = $role;
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
        if ($this->role == 'account_manager') {
            return $this->toMailForAccountManager($notifiable);
        }
        else {
            return $this->toMailForWorker($notifiable);
        }
    }

    public function toMailForAccountManager($notifiable)
    {
        return (new MailMessage)
            ->subject('Add to project!')
            ->line(_i('Hello %s', [$notifiable->first_name]))
            ->line(_i('You have been added to project "%s"', [$this->project->name]))
            ->action('Review Project', action('Resources\ProjectController@show', $this->project));
    }

    public function toMailForWorker($notifiable)
    {
        return (new MailMessage)
            ->subject('Task assigned!')
            ->line(_i('Hello %s', [$notifiable->first_name]))
            ->line(_i('You have been assigned a task for "%s"', [$this->project->name]))
            ->action('Review Project', action('Resources\ProjectController@show', $this->project));
    }
}
