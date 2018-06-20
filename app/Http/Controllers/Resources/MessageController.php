<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Services\Message\MessageManager;
use App\Services\Message\MessageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Musonza\Chat\Chat;
use Musonza\Chat\Notifications\MessageSent;
use App\Events\ChatMessage;

/**
 * Class MessageController
 * @package App\Http\Controllers
 */
class MessageController extends Controller
{
    /**
     * @var Chat
     */
    protected $chat;

    /**
     * @var MessageManager
     */
    protected $messageManager;

    /**
     * @var MessageRepository
     */
    protected $messageRepository;

    /**
     * MessageController constructor.
     * @param Chat $chat
     * @param MessageManager $messageManager
     * @param MessageRepository $messageRepository
     */
    public function __construct(
        Chat $chat,
        MessageManager $messageManager,
        MessageRepository $messageRepository
    )
    {
        $this->chat = $chat;
        $this->messageManager = $messageManager;
        $this->messageRepository = $messageRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $data = $this->messageRepository->conversations($request->user(), $this->chat);

        return
            $request->has('c') &&
            ! $request->user()->conversations()->where('id', $request->input('c'))->exists()
                ? redirect()->route('messages.index', ['c' => $data['conversations']->first()->id])
                : view('entity.chat.index', [
                'conversations' => $data['conversations'],
                'has_conversations' => $data['has_conversations']
            ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $message = $this->messageManager->create($request->user(), $this->chat, $request->input());
            broadcast(new ChatMessage($message));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        return view('entity.chat.show', $this->messageRepository->conversationById(Auth::user(), $this->chat->conversation($id), $id));
    }

    /**
     * @param $id
     * @return array
     */
    public function read($id)
    {
        $this->chat->conversation($id)->readAll(Auth::user());

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
