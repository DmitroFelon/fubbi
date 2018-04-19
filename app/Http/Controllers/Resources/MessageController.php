<?php

namespace App\Http\Controllers\Resources;

use App\Events\ChatMessage;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Musonza\Chat\Chat;
use Musonza\Chat\Facades\ChatFacade;
use Musonza\Chat\Notifications\MessageSent;


/**
 * Class MessageController
 * @package App\Http\Controllers
 */
class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Chat $chat, Request $request)
    {
        $user          = Auth::user();
        $conversations = $user->conversations;

        if ($user->role == Role::ADMIN) {
            $users = User::all();
            $users->each(function (User $user) use ($chat, $conversations) {
                if ($user->id == Auth::user()->id) {
                    return;
                }
                $conversation = $chat->getConversationBetween($user->id, Auth::user()->id);
                if (!$conversation) {
                    $conversation = $chat->createConversation([$user->id, Auth::user()->id]);
                    $conversation->update([
                        'data' => [
                            'title-' . $user->id        => Auth::user()->name,
                            'title-' . Auth::user()->id => $user->name
                        ]
                    ]);
                    $conversation->save();
                }
                $conversations->push($conversation);
            });
        }


        if ($user->role == Role::ACCOUNT_MANAGER) {

        }

        $conversations = $conversations->unique();

        if ($conversations->count() == 1 and !$request->has('c')) {
            return redirect()->action('Resources\MessageController@index', ['c' => $conversations->first()->id]);
        }

        return view('entity.chat.index', [
            'conversations'     => $conversations,
            'has_conversations' => $conversations->isNotEmpty()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Chat $chat)
    {
        $user            = Auth::user();
        $conversation_id = $request->input('conversation');
        $conversation    = $chat->conversation($conversation_id);

        if (!$conversation->users()->where('users.id', $user->id)->first()) {
            return ['error'];
        }

        $message = $chat->message($request->input('message'))->from($user->id)->to($conversation)->send();

        broadcast(new ChatMessage($message));

        return ['sent'];
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Chat $chat)
    {
        $user = Auth::user();

        $conversation = $chat->conversation($id);

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

        return view('entity.chat.show', $data);
    }

    /**
     * @param $id
     * @return array
     */
    public function read($id)
    {
        $user         = Auth::user();
        $conversation = ChatFacade::conversation($id);
        $conversation->readAll($user);

        return ['read'];
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clear()
    {
        $user = Auth::user();
        Auth::user()->unreadNotifications()->where('type', '=', MessageSent::class)->get()->markAsRead();
        return redirect()->back();
    }
}
