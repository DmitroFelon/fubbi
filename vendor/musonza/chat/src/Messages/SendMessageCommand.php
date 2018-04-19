<?php

namespace Musonza\Chat\Messages;

use Musonza\Chat\Conversations\Conversation;

class SendMessageCommand
{
    public $senderId;
    public $body;
    public $conversation;

    /**
     * @param \Musonza\Chat\Conversations\Conversation $conversation The conversation
     * @param string                                   $body         The body
     * @param int                                      $senderId     The sender identifier
     * @param string                                   $type         The type
     */
    public function __construct(Conversation $conversation, $body, $senderId, $type = 'text')
    {
        $this->conversation = $conversation;
        $this->body = $body;
        $this->type = $type;
        $this->senderId = $senderId;
    }
}
