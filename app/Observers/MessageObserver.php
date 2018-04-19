<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 12/26/17
 * Time: 6:42 PM
 */

namespace App\Observers;


use App\Notifications\Chat\PrivateMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Musonza\Chat\Facades\ChatFacade;
use Musonza\Chat\Messages\Message;

/**
 * Class MessageObserver
 * @package App\Observers
 */
class MessageObserver
{
    /**
     * @param Message $message
     */
    public function created(Message $message)
    {
        $conversation = $message->conversation;

        $recipients = $conversation->users->filter(function ($user) use ($message, $conversation) {
            return $message->user_id !== $user->id;
        });

        if (\Activity::users()->where('user_id', Auth::user()->id)->get()->isEmpty()) {
            Notification::send($recipients, new PrivateMessage($message));
        }

        Notification::send($recipients, new \App\Notifications\Chat\Message($message));
    }

}