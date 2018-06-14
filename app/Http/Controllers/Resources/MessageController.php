<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Services\Message\MessageManager;
use App\Services\Message\MessageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Musonza\Chat\Chat;
use Musonza\Chat\Facades\ChatFacade;
use Musonza\Chat\Notifications\MessageSent;
use App\Events\ChatMessage;

/**
 * Class MessageController
 * @package App\Http\Controllers
 */
class MessageController extends Controller
{
    /**
     * @param Chat $chat
     * @param Request $request
     * @param MessageRepository $messageRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Chat $chat, Request $request, MessageRepository $messageRepository)
    {
        return $messageRepository->conversations(Auth::user(), $chat, $request->input());
    }

    /**
     * @param Request $request
     * @param Chat $chat
     * @param MessageManager $messageManager
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Chat $chat, MessageManager $messageManager)
    {
        try {
            $message = $messageManager->create($request->user(), $chat, $request->input());
            broadcast(new ChatMessage($message));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * @param $id
     * @param Chat $chat
     * @param MessageRepository $messageRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id, Chat $chat, MessageRepository $messageRepository)
    {
        return view('entity.chat.show', $messageRepository->conversationById(Auth::user(), $chat->conversation($id), $id));
    }

    /**
     * @param $id
     * @return array
     */
    public function read($id)
    {
        ChatFacade::conversation($id)->readAll(Auth::user());
        return ['read'];
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clear()
    {
        Auth::user()->unreadNotifications()->where('type', '=', MessageSent::class)->get()->markAsRead();
        return redirect()->back();
    }
}
