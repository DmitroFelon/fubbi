@if(isset($notification->data['message']))

    <li>
        <div class="dropdown-messages-box">
            <div>
                <small class="pull-right text-navy">{{$notification->created_at->diffForHumans()}}</small>
                @if(isset($notification->data['link']))
                    <a href="{{url('notification/show/'.$notification->id)}}">
                        {{$notification->data['message']}}.
                    </a>
                @else
                    {{$notification->data['message']}}.
                @endif
                <br>
                <small class="text-muted">{{$notification->created_at}}</small>
            </div>
        </div>
    </li>
    <li class="divider"></li>


@endif