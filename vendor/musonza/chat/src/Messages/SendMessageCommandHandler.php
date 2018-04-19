<?php

namespace Musonza\Chat\Messages;

use Musonza\Chat\Commanding\CommandHandler;
use Musonza\Chat\Eventing\EventDispatcher;

class SendMessageCommandHandler implements CommandHandler
{
    protected $message;
    protected $dispatcher;

    /**
     * @param \Musonza\Chat\Eventing\EventDispatcher $dispatcher The dispatcher
     * @param Message                                $message    The message
     */
    public function __construct(EventDispatcher $dispatcher, Message $message)
    {
        $this->dispatcher = $dispatcher;
        $this->message = $message;
    }

    /**
     * Triggers sending the message.
     *
     * @param  $command  The command
     *
     * @return Message
     */
    public function handle($command)
    {
        $message = $this->message->send($command->conversation, $command->body, $command->senderId, $command->type);

        $this->dispatcher->dispatch($this->message->releaseEvents());

        return $message;
    }
}
