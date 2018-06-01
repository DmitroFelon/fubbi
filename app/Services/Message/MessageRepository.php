<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01.06.18
 * Time: 13:42
 */

namespace App\Services\Message;

use App\User;
use Musonza\Chat\Chat;
use App\Models\Role;


/**
 * Class MessageRepository
 * @package App\Services\Message
 */
class MessageRepository
{
    /**
     * @param User $user
     * @param $conversation
     * @param $id
     * @return array
     */
    public function conversationById(User $user, $conversation, $id)
    {
        if (!$conversation->users()->where('users.id', $user->id)->first()) {
            return ['error'];
        }
        $conversation->readAll($user);
        $messages = $conversation->messages()->orderBy('id', 'desc')->take(50)->get()->reverse();
        $data = [
            'chat_messages' => $messages,
            'participants'  => $conversation->users,
            'conversation'  => $id
        ];
        return $data;
    }

    /**
     * @param User $user
     * @param Chat $chat
     * @param array $params
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Http\RedirectResponse|\Illuminate\Support\Collection|mixed|\Musonza\Chat\Conversations\Conversation[]
     */
    public function conversations(User $user, Chat $chat, array $params)
    {
        $conversations = $user->conversations;
        if ($user->role == Role::ADMIN) {
            $conversations = $this->adminConversations($chat, $conversations, $user);
        }
        $conversations = $conversations->unique();
        if ($conversations->count() == 1 and !array_key_exists('c', $params)) {
            return redirect()->action('Resources\MessageController@index', ['c' => $conversations->first()->id]);
        }
        return $conversations;
    }

    /**
     * @param Chat $chat
     * @param $conversations
     * @param User $currentUser
     * @return mixed
     */
    protected function adminConversations(Chat $chat, $conversations, User $currentUser)
    {
        $users = User::all();
        $users->each(function (User $user) use ($chat, $conversations, $currentUser) {
            if ($user->id == $currentUser->id) {
                return;
            }
            $conversation = $chat->getConversationBetween($user->id, $currentUser->id);
            if (!$conversation) {
                $conversation = $this->createConversation($chat, $user, $currentUser);
            }
            $conversations->push($conversation);
        });
        return $conversations;
    }

    /**
     * @param Chat $chat
     * @param User $user
     * @param User $currentUser
     * @return \Musonza\Chat\Conversations\Conversation
     */
    protected function createConversation(Chat $chat, User $user, User $currentUser)
    {
        $conversation = $chat->createConversation([$user->id, $currentUser->id]);
        $conversation->update([
            'data' => [
                'title-' . $user->id        => $currentUser->name,
                'title-' . $currentUser->id => $user->name
            ]
        ]);
        $conversation->save();
        return $conversation;
    }
}