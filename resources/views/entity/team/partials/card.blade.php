<div class="col-lg-4">
    <div class="ibox">
        <div class="ibox-title">
            <a href="{{url()->action('Resources\TeamController@edit', $team)}}"
               class="btn btn-white btn-xs pull-right">
                {{_i('Edit team')}}
            </a>
            <h5>{{$team->name}}</h5>
        </div>
        <div class="ibox-content">
            <h4>{{_i('Info about')}} {{$team->name}}</h4>
            <p>
                {{$team->description}}
            </p>
            <div class="row  m-t-sm">
                <div class="col-sm-4">
                    <small>{{_i('Total Projects')}}</small>
                    {{$team->projects->count()}}
                </div>
            </div>
            <hr>
            <div class="team-members">
                <h4>{{_i('Team Members')}}</h4>
                <a data-id="{{$team->owner->id}}" target="_blank" href="{{url()->action('Resources\UserController@show', $team->owner)}}">
                    {{$team->owner->name}}
                </a>
                <span class="label label-primary pull-right">{{_i('Owner')}}</span>
                <br>
                @foreach($team->users as $user)
                    <a target="_blank" href="{{url()->action('Resources\UserController@show', $user)}}">
                        {{$user->name}}
                    </a>
                    @if($user->id == $team->owner_id)
                        <span class="label label-primary pull-right">{{_i('Owner')}}</span>
                    @else
                        <span class="label label-default pull-right">
                            {{$user->roles()->first()->display_name}}
                        </span>
                    @endif
                    <br>
                @endforeach
            </div>
        </div>
    </div>
</div>
