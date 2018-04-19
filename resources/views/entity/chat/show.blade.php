<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        {{ Form::open(['method' => 'POST', "id" => 'chat-form', 'action' => 'Resources\MessageController@store' ]) }}
        {{Form::hidden('conversation', $conversation, ['id' => 'chat-id'])}}
        <div class="col-md-10">
            <div id="chat-discussion" class="chat-discussion">
                @each('entity.chat.partials.message', $chat_messages, 'chat_message')
            </div>
            @include('entity.chat.partials.input')
        </div>
        <div class="col-md-2">
            <div class="chat-users">
                <div class="users-list">
                    @each('entity.chat.partials.participant', $participants, 'user')
                </div>
            </div>
        </div>
        {{Form::close()}}
    </div>
</div>
