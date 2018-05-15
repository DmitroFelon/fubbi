@extends('master')

@section('content')

    <div class="ibox">
        <div class="ibox-title">
            <h5>Settings</h5>
        </div>
        <div class="ibox-content">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="tabs-container">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a data-toggle="tab" href="#tab-main">
                            <i class="fa fa-user"></i> {{_i('Profile')}}
                        </a>
                    </li>
                    <li class="">
                        <a data-toggle="tab" href="#tab-notifications">
                            <i class="fa fa-bell "></i> {{_i('Notifications')}}
                        </a>
                    </li>
                    @role([\App\Models\Role::CLIENT])
                    <li class="">
                        <a data-toggle="tab" href="#tab-billing">
                            <i class="fa fa-credit-card "></i> {{_i('Billling info')}}
                        </a>
                    </li>
                    @endrole

                </ul>
                <div class="tab-content">
                    <div id="tab-main" class="tab-pane active">
                        <div class="panel-body">
                            @include('entity.user.settings.account')
                        </div>
                    </div>
                    <div id="tab-notifications" class="tab-pane">
                        <div class="panel-body">
                            @include('entity.user.settings.notifications')
                        </div>

                    </div>
                    @role([\App\Models\Role::CLIENT])
                    <div id="tab-billing" class="tab-pane">
                        <div class="panel-body">
                            @include('entity.user.settings.billing')
                        </div>
                    </div>
                    @endrole
                </div>
            </div>
        </div>
    </div>

@endsection



@section('before-scripts')

    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

@endsection
