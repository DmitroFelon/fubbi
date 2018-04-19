<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 2/12/18
 * Time: 4:16 PM
 */

namespace App\Notifications\Worker;

use App\Models\Project;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HasTask extends Notification
{

    protected $project;
    protected $overdue;
    protected $tasks;

    /**
     * Create a new notification instance.
     *
     * @param Project $project
     * @param int $overdue
     */
    public function __construct(Project $project, $overdue = 1, $tasks = '')
    {
        $this->project = $project;
        $this->overdue = $overdue;
        $this->tasks   = $tasks;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ($notifiable->disabled_notifications()->where('name', get_class($this))->first())
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
        $subject = ($this->overdue > 1)
            ? 'Urgent (overdue ' . $this->overdue . ' days)'
            : 'Overdue 1 Day';

        $line = ($this->overdue > 1)
            ? 'You have an urgent task to address that is overdue.'
            : 'You have a task overdue by one day';

        return (new MailMessage)
            ->subject($subject)
            ->line('Hi ' . $notifiable->first_name)
            ->line($line)
            ->line($this->tasks)
            ->action('Review', action('Resources\ProjectController@show', $this->project));
    }

}