<?php

namespace App\Notifications\Task;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Overdue extends Notification
{
    use Queueable;

    protected $project;
    protected $task;
    protected $overdue;

    /**
     * Create a new notification instance.
     *
     * @param string $task
     * @param int $overdue
     */
    public function __construct(Project $project, string $task, $overdue = 1)
    {
        $this->project = $project;
        $this->task    = $task;
        $this->overdue = $overdue;
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
            ->subject('Overdue ' . $this->overdue . ' Day')
            ->line('Hi ' . $notifiable->first_name)
            ->line('You have a task overdue by one day')
            ->action('Review', action('Resources\ProjectController@show', $this->project));
    }

}
