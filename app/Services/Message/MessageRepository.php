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
     * @var SearchSuggestion
     */
    protected $searchSuggestion;

    /**
     * MessageRepository constructor.
     * @param SearchSuggestion $searchSuggestion
     */
    public function __construct(SearchSuggestion $searchSuggestion)
    {
        $this->searchSuggestion = $searchSuggestion;
    }

    /**
     * @param User $user
     * @param $conversation
     * @param $id
     * @return array
     */
    public function conversationById(User $user, $conversation, $id)
    {
        if (! $conversation->users()->where('users.id', $user->id)->first()) {

            return ['error'];
        }
        $conversation->readAll($user);
        $data = [
            'conversation'    => $id,
            'participants'    => $conversation->users,
            'userSuggestions' => $this->searchSuggestion->toView($conversation),
            'chat_messages'   => $conversation->messages()->orderBy('id', 'desc')->take(50)->get()->reverse(),
        ];

        return $data;
    }

    /**
     * @param User $user
     * @param Chat $chat
     * @return mixed
     */
    public function conversations(User $user, Chat $chat)
    {
        $conversations = $user->conversations;
        if ($user->role == Role::ADMIN) {
            $conversations = $this->adminConversations($chat, $conversations, $user);
        }
        $conversations = $conversations->unique();
        $data['conversations'] = $conversations;
        $data['has_conversations'] = $conversations->isNotEmpty();

        return $data;
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
            if (! $conversation) {
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

        return $conversation;
    }
}