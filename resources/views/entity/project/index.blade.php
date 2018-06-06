@extends('master')

@section('before-content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-4">
            <h2>{{_i('Projects')}}</h2>
        </div>
    </div>
@endsection

@section('content')
    <div class="ibox">
        <div class="ibox-title">
            <h5>{{_i('All projects assigned to this account')}}</h5>
        </div>
        <div class="ibox-content">
            <div class="row m-b-sm m-t-sm">
                @if(Auth::user()->role != \App\Models\Role::CLIENT)
                    @include('entity.project.partials.search')
                @endif()
            </div>
            <hr>
            <div class="project-list">
                <table class="table table-hover">
                    <tbody>
                    @foreach($projects as $project)
                        @include('entity.project.partials.row')
                    @endforeach
                    </tbody>
                </table>
                @if(method_exists($projects, 'links'))
                    <div class="text-center">{{ $projects->links() }}</div>
                @endif

            </div>
        </div>
    </div>
@endsection
