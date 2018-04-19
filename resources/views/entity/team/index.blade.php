@extends('master')

@section('before-content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>
                {{_i('Teams board')}}
            </h2>
        </div>
        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
            <a href="{{action('Resources\TeamController@create')}}" class="m-t-md btn btn-primary">
                {{_i('Create a new team')}}
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        @each('entity.team.partials.card', $teams, 'team' )
    </div>
@endsection
