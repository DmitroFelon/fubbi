@extends('master')

@section('content')

    <div class="ibox-content">
        <div class="ibox">
            <div class="ibox-title">
                <h5>{{__('All projects assigned to this account')}}</h5>
                <div class="ibox-tools">
                    <a target="_blank" href="{{route('projects.create')}}" class="btn btn-primary btn-xs">{{__('Create new project')}}</a>
                </div>
            </div>
            <div class="ibox-content">
                <div class="project-list">
                    <table class="table table-hover">
                        <tbody>
                        @foreach($projects as $project)
                            @include('entity.project.partials.card')
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection