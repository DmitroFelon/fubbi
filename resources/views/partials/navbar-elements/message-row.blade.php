@if(isset($message_notification->data['message']))
    <li>
        <div class="dropdown-messages-box">
            <div>
                <small class="pull-right text-navy">{{$message_notification->created_at->diffForHumans()}}</small>
                @if(isset($message_notification->data['link']))
                    <a href="{{url('notification/show/'.$message_notification->id)}}">
                        {{$message_notification->data['message']}}.
                    </a>
                @else
                    {{$message_notification->data['message']}}.
                @endif
                <br>
                <small class="text-muted">{{$message_notification->created_at}}</small>
            </div>
        </div>
    </li>
    <li class="divider"></li>
@endif