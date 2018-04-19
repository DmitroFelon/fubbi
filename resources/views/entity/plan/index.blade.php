@extends('master')

@section('content')
    <div class="ibox">
        <div class="ibox-title">
            <h5>{{_i('All plans')}}</h5>
            <div class="ibox-tools">

            </div>
        </div>
        <div class="ibox-content">
            <div class="project-list">
                <table class="table table-hover">
                    <tbody>
                    @foreach($plans as $plan)
                        @include('entity.plan.partials.card')
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection