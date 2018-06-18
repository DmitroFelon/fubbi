@extends('master')


@section('before-content')
    @if(\Illuminate\Support\Facades\Auth::user()->hasInvitetoTeam($team->id))
        <div class="row wrapper border-bottom blue-bg page-heading">
            <h3 class="text-center m-t-lg">
                {{_i('You are invited to this team')}}
            </h3>
            <div class="col-sm-6 text-center">
                <a href="{{ route('accept.team.invite', ['team' => $team->id]) }}"
                   class="btn btn-primary m-t-md">{{_i('Accept')}}</a>
            </div>
            <div class="col-sm-6 text-center">
                <a href="{{ route('decline.team.invite', ['team' => $team->id]) }}"
                   class="btn btn-danger m-t-md">{{_i('Decline')}}</a>
            </div>
        </div>
    @endif
@endsection

@section('content')
    @include('entity.team.partials.card')
@endsection