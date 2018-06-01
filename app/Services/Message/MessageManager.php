<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01.06.18
 * Time: 12:53
 */

namespace App\Services\Message;

use App\Events\ChatMessage;
use App\User;
use Musonza\Chat\Chat;

/**
 * Class MessageManager
 * @package App\Services\Message
 */
class MessageManager
{
    /**
     * @param User $user
     * @param Chat $chat
     * @param array $params
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(User $user, Chat $chat, array $params)
    {
        $conversation = $chat->conversation($params['conversation']);
        try {
            $message = $chat->message($params['message'])->from($user->id)->to($conversation)->send();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
        broadcast(new ChatMessage($message));
    }
}