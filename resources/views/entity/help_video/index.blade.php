@extends('master')

@section('content')
    <div class="ibox">
        <div class="ibox-title">
            <h5>{{ _i('Help Videos') }}</h5>
            <div class="ibox-tools">
                <a href="{{action('Resources\HelpVideosController@create')}}"
                   class="btn btn-primary btn-xs">{{ _i('Add new video') }}</a>
            </div>
        </div>
        <div class="ibox-content">
            <div class="m-b-lg">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-lg-offset-1">
                        <div class="m-t-md">
                            <strong>Found {{ $videos->count() }} videos.</strong>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover issue-tracker">
                                <tbody>
                                @foreach($videos as $video)
                                    <tr>
                                        <td class="project-title">
                                            <a href="{{action('Resources\HelpVideosController@show', $video)}}">{{ $video->name }}</a>
                                            <br/>
                                            <small>{{$video->created_at}}</small>
                                        </td>
                                        <td>
                                            <img src="{{$video->thumbnail}}" alt="">
                                        </td>
                                        <td>
                                            <strong>{{_i('Path')}}: </strong> {{ $video->url }}
                                        </td>
                                        <td>
                                            <strong>{{_i('Page')}}
                                                : </strong> {{ implode(', ', $video->page->pluck('name')->toArray() ) }}
                                        </td>
                                        <td>
                                            <a href="{{action('Resources\HelpVideosController@edit', $video)}}"
                                               class="btn btn-white btn-sm blue-bg">
                                                <i class="fa fa-pencil"></i> {{_i('Edit')}}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{action('Resources\HelpVideosController@destroy', $video)}}"
                                               class="btn btn-white btn-sm red-bg">
                                                <i class="fa fa-pencil"></i> {{_i('Delete')}}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $videos->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection