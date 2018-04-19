@extends('master')


@section('content')

    <div class="ibox">
        <div class="ibox-title">
            <h5>{{ _i('Issues') }}</h5>
            <div class="ibox-tools">
                <a href="{{action('Resources\IssueController@create')}}"
                   class="btn btn-primary btn-xs">{{ _i('Add new issue') }}</a>
            </div>
        </div>
        <div class="ibox-content">
            <div class="m-b-lg">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-lg-offset-1">
                        <div class="m-t-md">
                            <strong>Found {{ $issues->count() }} issues.</strong>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover issue-tracker">
                                <tbody>
                                @foreach($issues as $issue)
                                    <tr>
                                        <td>
                                            {!! $issue->printState() !!}
                                        </td>
                                        <td class="">
                                            <a href="{{action('Resources\IssueController@show', $issue)}}">
                                                ISSUE-{{$issue->id}}
                                            </a>
                                            <br>
                                            <small>
                                                {{$issue->title}}
                                            </small>
                                        </td>
                                        <td>
                                            {{$issue->user->name}}
                                        </td>
                                        <td>
                                            <strong>{{_i('Created')}} : </strong>
                                            <br class="hidden-lg">
                                             {{$issue->created_at->diffForHumans()}}
                                            <small class="hidden-md hidden-sm hidden-xs">
                                                ({{$issue->created_at->format('d.m.Y h:i a')}})
                                           </small>
                                        </td>
                                        <td>
                                            <strong>{{_i('Fixed')}} : </strong>
                                            <br class="hidden-lg">
                                             {{$issue->updated_at->diffForHumans()}}
                                            <small class="hidden-md hidden-sm hidden-xs">
                                                ({{$issue->updated_at->format('d.m.Y h:i a')}})
                                           </small>
                                        </td>
                                        <td class="text-right">
                                            @foreach($issue->tags as $tag)
                                                <span class="label label-primary m-l-xs m-r-xs">{{$tag->name}}</span>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $issues->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection