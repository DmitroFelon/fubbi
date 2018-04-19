{{Form::open(['method' => 'POST', 'action' => 'SettingsController@save', 'id' => 'settings-main-notifications'])}}
<h3 class="text-center">{{_i('Disable notifications')}}</h3>
<br>
<div class="row">
    @foreach($notifications_checkboxes as $name => $label)
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
            @include('entity.user.partials.checkbox')
        </div>
    @endforeach
</div>

{{Form::submit(_i('Save'), ['class' => 'btn btn-primary m-t-md'])}}

{{Form::close()}}