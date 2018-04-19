<?php

namespace Musonza\Chat\Eventing;

use Musonza\Chat\Chat;

class EventDispatcher
{
    protected $event;

    public function dispatch(array $events)
    {
        $customDispatcher = Chat::eventDispatcher();

        if ($customDispatcher && class_exists($customDispatcher)) {
            app($customDispatcher)->dispatch($events);
        }
    }
}
