@extends('master')

@section('content')

    <div class="ibox">
        <div class="ibox-title">
            <h5>{{_i('Video "%s"', [$helpVideo->name])}}</h5>
            <div class="ibox-tools">

            </div>
        </div>
        <div class="ibox-content">

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <iframe id="ytplayer" type="text/html" width="640" height="360"
                            src="http://www.youtube.com/embed/{{$helpVideo->youtube_id}}?autoplay=0&fs=1"
                            frameborder="0"></iframe>
                </div>
            </div>

            <div class="row m-t-lg">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h3>{{_i('Pages:')}}</h3>
                    {{ implode(',<br>', $helpVideo->page->pluck('name')->toArray() ) }}
                </div>
            </div>
        </div>

    </div>

@endsection