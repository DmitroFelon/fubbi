@extends('master')

@section('before-content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-4">
            <h2>{{_i('plans')}}</h2>
        </div>
    </div>

@endsection

@section('content')
    <div class="ibox">
        <div class="ibox-title">
            <h5>
                {{ _i('Modify %s plan for project "%s"', [$plan_id, $project->name]) }}
            </h5>
            {{--<div class="ibox-tools">--}}
                {{--<a target="_blank" href="{{url()->action('Resources\PlanController@edit', $plan_id)}}"--}}
                   {{--class="btn btn-primary btn-xs">{{_i('Edit plan')}}</a>--}}
            {{--</div>--}}
        </div>
        <div class="ibox-content">
            <div class="project-list">
                {!! Form::open(['action' => ['Project\PlanController@update', $project->id, $plan_id], 'method' => 'put']) !!}
                @include('entity.plan.form')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection