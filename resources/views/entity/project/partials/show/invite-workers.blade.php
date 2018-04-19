{{Form::open([ 'method' => 'POST', 'action' => ['Resources\ProjectController@invite_users', $project ] ])}}

<div class="row">
    @foreach($users as $id => $name)
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="i-checks">
                <label>
                    <input type="checkbox"
                           name="users[{{$id}}]"
                           value="1"> <i></i>
                    {{$name}}
                </label>
            </div>
        </div>
    @endforeach
</div>

<div class="row m-t-md">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        {{Form::submit(_i('Attach'), ['class' => 'btn btn-primary'])}}
    </div>
</div>

{{Form::close()}}