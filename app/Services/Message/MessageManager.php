<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01.06.18
 * Time: 12:53
 */

namespace App\Services\Message;

use App\User;
use Musonza\Chat\Chat;
use App\Jobs\NewMessageNotification;
use App\Services\User\UserRepository;

/**
 * Class MessageManager
 * @package App\Services\Message
 */
class MessageManager
{
    protected $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    /**
     * @param User $user
     * @param Chat $chat
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function create(User $user, Chat $chat, array $params)
    {
        $conversation = $chat->conversation($params['conversation']);
        $message = $chat->message($params['message'])
            ->from($user->id)
            ->to($conversation)
            ->for($user)
            ->send();

        return $this->setMessageRecipients($message, $params['message'], $conversation->id);
    }

    /**
     * @param $messageInstance
     * @param $fullMessage
     * @param $conversationId
     * @return mixed
     */
    public function setMessageRecipients($messageInstance, $fullMessage, $conversationId)
    {
        $messageParts = explode(' ', $fullMessage);
        $recipients = [];
        foreach ($messageParts as $part) {
            if (preg_match('/^@.{1,}/', $part)) {
                $recipient = substr($part, 1);
                $user = $this->userRepository->findByUsername($recipient);
                if ($user) {
                    NewMessageNotification::dispatch($user, $conversationId);
                    array_push($recipients, $recipient);
                }
            }
        }
        if (! empty($recipients)) {
            $messageInstance->recipients = json_encode($recipients);
            $messageInstance->save();
        }

        return $messageInstance;
    }
}