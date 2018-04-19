@extends('master')

@section('content')
    <div class="ibox">
        <div class="ibox-title">
            <h5>
                {{_i('Users')}}
            </h5>
            <div class="ibox-tools">
                @role([\App\Models\Role::ADMIN])
                <a target="_blank" href="{{url()->action('Resources\UserController@create')}}"
                   class="btn btn-primary btn-xs">{{_i('Create new User')}}</a>
                @endrole
            </div>
        </div>
        <div class="ibox-content">
            @include('entity.user.partials.search')
            <div class="clients-list">
                <span class="pull-right small text-muted">{{_i('Total')}}: <small>{{$users->count()}}</small></span>
                <ul class="nav nav-tabs">
                    @foreach($roles as $role)
                        @include('entity.user.partials.top-tab')
                    @endforeach
                </ul>
                <div class="tab-content">
                    @foreach($groupedByRoles as $role => $users)
                        @include('entity.user.partials.tab')
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection