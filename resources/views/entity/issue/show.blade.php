@extends('master')

@section('content')

    <div class="ibox">
        <div class="ibox-title">
            <h5>{{_i('Issue %d', [$issue->id])}} </h5>
            @foreach($issue->tags as $tag)
                <span class="label label-primary m-l-xs m-r-xs">{{$tag->name}}</span>
            @endforeach
            <div class="ibox-tools">

                @role([\App\Models\Role::ADMIN])

                {!! Form::model( $issue, ['route' => ['issues.update', $issue], 'method' => 'PUT' ] ) !!}

                {!! Form::submit(_i('Mark as fixed'), ['class' => 'btn btn-success text-white btn-xs']) !!}

                {!! Form::close() !!}

                @endrole
            </div>
        </div>
        <div class="ibox-content">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <h3 class="text-center">
                            {{$issue->title}}
                        </h3>
                        <div class="text-center">
                            <small>
                                {{$issue->created_at->format('d.m.Y h:i a')}}
                            </small>
                        </div>
                    </div>
                    <div class="row p-lg">

                        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 col-lg-offset-2">
                            <p class="p-lg border-left-right border-top-bottom">
                                {{$issue->body}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection