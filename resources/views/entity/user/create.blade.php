@extends('master')


@section('before-content')

@endsection


@section('content')
    <div class="ibox">
        <div class="ibox-title">
            <h5>
                {{_i('Create a new User')}}
            </h5>
        </div>

        <div class="ibox-content">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            {{Form::model(\App\User::class, ['route' => ['users.store']])}}

            <h3 class="text-center">
                {{_i('Name')}}
            </h3>

            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    {!! Form::bsText('first_name', null, _i('First Name'), null, [], 'text') !!}
                </div>

                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    {!! Form::bsText('last_name', null, _i('Last Name'), null, ['required'], 'text') !!}
                </div>
            </div>

            <h3 class="text-center">
                {{_i('Contacts')}}
            </h3>

            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    {!! Form::bsText('email', null, _i('Email'), null, ['required'], 'email') !!}
                </div>

                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    {!! Form::bsText('phone', null, _i('Phone'), null, ['required'], 'phone') !!}
                </div>
            </div>

            <h3 class="text-center">
                {{_i('Password')}}
            </h3>

            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    {!! Form::bsText('password', null, _i('Password'), null, [], 'password') !!}
                </div>

                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    {!! Form::bsText('password_confirmation', null, _i('Repeat Password'), null, [], 'password') !!}
                </div>
            </div>

            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    {!! Form::bsSelect('role', Illuminate\Support\Arr::pluck(\App\Models\Role::all(['id', 'display_name']), 'display_name', 'id'),
        null, _i("Please, select user's role"), '', ['required']) !!}
                </div>

                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    {!! Form::bsSelect('team', ['' => 'select team'] +Illuminate\Support\Arr::pluck(\App\Models\Team::all(['id', 'name']), 'name', 'id'),
         null, _i("Please, select user's team if necessary"), '') !!}
                </div>
            </div>

            {{Form::submit(_i('Create User'), ['class' => 'btn btn-primary'])}}

            {{Form::close()}}
        </div>
    </div>

@endsection