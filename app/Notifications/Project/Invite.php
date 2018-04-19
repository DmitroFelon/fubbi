<?php

namespace App\Notifications\Project;

use App\Models\Invite as Invitation;
use App\Notifications\NotificationPayload;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class Invite extends Notification
{
    use Queueable;

    protected $invitation;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\Invite $invitation
     */
    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $email = (new MailMessage)
            ->subject(_i('Invitation to project %s', [$this->invitation->invitable->getInvitableName()]))
            ->line(_i('Hello %s', [$notifiable->name]))
            ->line(_i('You have beed invited to project %s. Please apply or decline it', [$this->invitation->invitable->getInvitableName()]))
            ->action('Review Project', $this->invitation->invitable->getInvitableUrl())
            ->line('Thank you for using our application!');

        if (method_exists($this->invitation->invitable, 'export')) {
            $email->line('<a href="' . Storage::url('exports/' . $this->invitation->invitable->export() . '">Project Export</a>'));
        }

        return $email;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $notification = NotificationPayload::make(
            _i(
                'You have beed invited to project %s. Please apply or decline it',
                [$this->invitation->invitable->getInvitableName()]
            ),
            $this->invitation->invitable->getInvitableUrl(),
            get_class($this->invitation->invitable),
            $this->invitation->invitable->getInvitableId()
        );

        return $notification->toArray();
    }
}
