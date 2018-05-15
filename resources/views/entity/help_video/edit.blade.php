@extends('master')

@section('content')

    <div class="ibox">
        <div class="ibox-title">
            <h5>{{_i('Add video')}}</h5>
            <div class="ibox-tools">

            </div>
        </div>
        <div class="ibox-content">
            <img class="m-md" src="{{$helpVideo->thumbnail}}" alt="">
            {!! Form::model($helpVideo, ['method' => 'PUT', 'role'=>'form', 'route'=>['help_videos.update', $helpVideo]]) !!}
            {!! Form::bsText('name', $helpVideo->name, _i('Name'), null, ['required'], 'text') !!}
            @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif

            {!! Form::bsText('url', $helpVideo->url, _i('Full url or Youtube id'), null, ['required'], 'text') !!}
            @if ($errors->has('url'))
                <span class="help-block">
                    <strong>{{ $errors->first('url') }}</strong>
                </span>
            @endif

            {!! Form::bsSelect('page', $pages, (isset($helpVideo->page->route)?$helpVideo->page->route:null), _i(''), '', ['required']) !!}
            {{Form::submit('Save Video', ['class' => 'form-control btn btn-primary'])}}
            {!! Form::close() !!}
        </div>
    </div>

@endsection
