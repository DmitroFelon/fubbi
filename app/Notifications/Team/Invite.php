<?php

namespace App\Notifications\Team;


use App\Models\Invite as Invitation;
use App\Notifications\NotificationPayload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\View;

/**
 * Class Invite
 * @package App\Notifications\Team
 */
class Invite extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var Invitation
     */
    protected $invitation;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\Invite $invite
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
        return ['mail', 'database', 'broadcast'];
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
            ->subject('Invitation to team')
            ->line(_i('You have beed invited to the team %s. Please apply or decline it', [$this->invitation->invitable->getInvitableName()]))
            ->action('Review Invitation', $this->invitation->invitable->getInvitableUrl())
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {

        $notification = $this->makePayload();

        return $notification->toArray();
    }

    /**
     * @param $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {

        $data = $this->makePayload();

        $notification             = new \stdClass();
        $notification->created_at = now();
        $notification->data       = $data->toArray();
        $notification->id         = $this->id;

        $navbar_message = View::make('partials.navbar-elements.alert-row', ['notification' => $notification]);

        return new BroadcastMessage(['navbar_message' => $navbar_message->render()]);
    }

    /**
     * @return NotificationPayload
     */
    private function makePayload()
    {
        $notification = NotificationPayload::make(
            _i('You have beed invited to the team: %s', [$this->invitation->invitable->getInvitableName()]),
            $this->invitation->invitable->getInvitableUrl(),
            get_class($this->invitation->invitable),
            $this->invitation->invitable->getInvitableId()
        );
        return $notification;
    }

}
