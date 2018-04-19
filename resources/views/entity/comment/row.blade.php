<div class="social-feed-box">
    <div class="social-avatar">
        <div class="media-body">
            <a target="_blank" href="{{url()->action('Resources\UserController@show', $comment->creator)}}">
                {{$comment->creator->name}}
            </a>
            <small class="text-muted">
                {{$comment->created_at->diffForHumans()}}
            </small>
        </div>
    </div>
    <div class="social-body">
        @if($comment->title)
            <h3>{{$comment->title}}</h3>
        @endif
        <p>
            {{$comment->body}}
        </p>
    </div>
</div>
