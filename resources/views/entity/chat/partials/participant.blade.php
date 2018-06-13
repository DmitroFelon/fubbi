<div class="chat-user">
    <span data-user-id="{{$user->id}}"  class="chat-user-status pull-right label">
        {{($user->isActive())?'Online':'Offline'}}
    </span>

    <div class="chat-user-name">
        <a href="#">{{$user->name}}</a>
        <br>
        <a href="#">{{ '(@' . $user->username . ')'}}</a>
    </div>
</div>