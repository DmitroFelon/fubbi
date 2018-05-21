<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Fubbi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.css"/>
    <link rel="stylesheet" href="{!! asset('css/vendor.css') !!}"/>
    <link rel="stylesheet" href="{!! asset('css/app.css') !!}"/>
</head>

@include('header')

<body>

@yield('message')

<!-- Wrapper-->
<div id="wrapper">
    <!-- Navigation -->
@include('layouts.navigation')



<!-- Page wraper -->
    <div id="page-wrapper" class="gray-bg">

    @auth
    @include('layouts.topnavbar')
    @endauth

    @include('partials.messages')

    @yield('before-content')

    <!-- Main view  -->
        <div class="row">
            <div class="col-lg-12">
                <div class="wrapper wrapper-content animated fadeInUp">
                    @yield('content')
                </div>
            </div>
        </div>
        <!-- End Main view  -->

    @yield('after-content')

    <!-- Footer -->
        @include('footer')

    </div>
    <!-- End page wrapper-->

</div>
<!-- End wrapper-->
<script data-cfasync='false'>
    var stripe_pub = "{{config('services.stripe.key')}}";
    var user = @json((\Illuminate\Support\Facades\Auth::check())?\Illuminate\Support\Facades\Auth::user():'');
    var conversation_id = null;
    var is_chat = false;
    var help_video_src = @json($help_video_src);
</script>

<div id="help-video-wrapper">
    @isset($help_video_src)
    @foreach($help_video_src as $video)
        <button data-name="{{$video->name}}" data-player="{{$video->player}}"
                class="btn btn-warning btn-lg question-btn">
            <i class="fa fa-youtube"></i> <span class="bold">{{$video->name}}</span>
        </button>
    @endforeach
    @endisset
</div>

@yield('before-scripts')

<script data-cfasync='false' src="{!! asset('js/lib.js') !!}" type="text/javascript"></script>
<script data-cfasync='false' src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script data-cfasync='false' src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script data-cfasync='false' src="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css" property=""/>
<script data-cfasync='false' type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
<script data-cfasync='false' type="text/javascript" src="{!! asset('js/app.js') !!}?v=1"></script>

@yield('scripts')

<script>
    jQuery(document).ready(function ($) {
        jQuery(".condition-cb").on('change', function () {
            var target = $("#" + $(this).attr('data-target'));
            if ($(this).is(':checked')) {
                target.show();
            } else {
                target.hide();
            }
        });

        $(".alert").on('close.bs.alert', function () {
            $.get("{{url('coockie')}}/" + $(this).attr('data-key') + "/1")
        });

    });
</script>

</body>
</html>
