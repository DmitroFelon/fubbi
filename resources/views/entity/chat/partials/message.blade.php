<div data-id="{{$chat_message->id}}"
     class="chat-message {{($chat_message->sender->id == \Illuminate\Support\Facades\Auth::user()->id)?'right':'left'}}">
    <div class="row">
        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
            <div title="{{$chat_message->sender->role}}"
                 class="role-placeholder {{$chat_message->sender->getBadgeColor()}}">
                {{ucfirst($chat_message->sender->role[0])}}
            </div>
        </div>
        <div class="col-xs-11 col-sm-11 col-md-11 col-lg-11">
            <div class="message">
                <a class="message-author" href="#"> {{$chat_message->sender->name}} </a>
                <span class="message-date"> {{$chat_message->created_at}} </span>
                <span class="message-content">
                {{$chat_message->body}}
                </span>
            </div>
        </div>
    </div>
</div>