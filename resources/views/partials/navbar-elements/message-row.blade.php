<li class="top-message-list-item" data-conversation-id="{{$message_notification->conversation->id}}">
    <a href="{{action('Resources\MessageController@index', ['c' => $message_notification->conversation->id ])}}">
        <div>
            <i class="fa fa-envelope fa-fw"></i>
            <strong>{{$message_notification->message->sender->name}}:</strong>
            <small>
                {{str_limit($message_notification->message->body, 7, ' ...') }}
            </small>
            <span class="pull-right text-muted small">
                {{ $message_notification->conversation->data['title'] ?? $message_notification->conversation->data['title-'.Auth::id()] }}
            </span>
        </div>
    </a>
</li>
<li data-divider-id="{{$message_notification->conversation->id}}" class="divider"></li>