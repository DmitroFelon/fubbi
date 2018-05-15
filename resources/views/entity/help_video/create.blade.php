@extends('master')

@section('content')

    <div class="ibox">
        <div class="ibox-title">
            <h5>{{_i('Add video')}}</h5>
            <div class="ibox-tools">

            </div>
        </div>
        <div class="ibox-content">

            {!! Form::open(['method' => 'POST', 'role'=>'form', 'route'=>['help_videos.store']]) !!}

            {!! Form::bsText('name', null, _i('Name'), null, ['required'], 'text') !!}
            @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif

            {!! Form::bsText('url', null, _i('Full url or Youtube id'), null, ['required'], 'text') !!}
            @if ($errors->has('url'))
                <span class="help-block">
                    <strong>{{ $errors->first('url') }}</strong>
                </span>
            @endif

            {!! Form::bsSelect('page', $pages, null, _i(''), '', ['required']) !!}

            {{Form::submit('Save Video', ['class' => 'form-control btn btn-primary'])}}

            {!! Form::close() !!}
        </div>
    </div>

@endsection
