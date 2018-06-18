@extends('master')

@section('content')
    <div class="ibox">
        <div class="ibox-title">
            <h5>{{_i('Create a new team')}}</h5>
            <div class="ibox-tools">

            </div>
        </div>
        <div class="ibox-content">

            <div class="row">
                {{Form::open(['id' => 'team-form', 'action' => 'Resources\TeamController@store'])}}
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-6 form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    {!! Form::bsText( 'name', null, _i('Name of the new team'), null) !!}
                    @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    {!! Form::bsText( 'description', null, _i('Short description of the new team'), null, [] ) !!}
                    @if ($errors->has('description'))
                        <span class="help-block">
                            <strong>{{ $errors->first('description') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-6">
                    {!! Form::bsSelect('owner_id', $owner_users,
                    \Illuminate\Support\Facades\Auth::user()->id, _i("Owner of the new tem"), '', ['required']) !!}
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h2>{{_i('Invite users')}}</h2>

                    <div class="row">
                        @foreach($invitable_users as $id => $name)
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
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
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    {{Form::submit(_i('Create'), ['class' => 'btn btn-primary'])}}
                </div>

                {{Form::close()}}
            </div>
        </div>
    </div>
@endsection
