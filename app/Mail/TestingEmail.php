<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;

/**
 * Class RegistrationConfirmation
 * @package App\Mail
 */
class TestingEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var MailMessage
     */
    protected $mailMessage;

    /**
     * Create a new notification instance.
     *
     * @param User $user
     * @param MailMessage $mailMessage
     */
    public function __construct(MailMessage $mailMessage)
    {
       
        $this->mailMessage = $mailMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('imad.bazzal.93@gmail.com')
            ->markdown('notifications.email', ['mailMessage' => $this->mailMessage]);
    }
}
