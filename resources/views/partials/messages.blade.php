@if(session('error') or session('success') or session('info'))
    <div class="container-fluid m-t-md">
        @if (session('error'))
            @foreach(collect(session()->pull('error')) as $message)
                @if(!\Illuminate\Support\Facades\Session::has(base64_encode($message)) and $message != '')
                    <div data-key="{{base64_encode($message)}}" class="alert alert-danger alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        {!! $message !!}
                    </div>
                @endif
            @endforeach
        @endif

        @if (session('success'))
            @foreach(collect(session()->pull('success')) as $message)
                @if(!\Illuminate\Support\Facades\Session::has(base64_encode($message)) and $message != '')
                    <div data-key="{{base64_encode($message)}}" class="alert alert-success alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        {!! $message !!}
                    </div>
                @endif
            @endforeach
        @endif

        @if (session('info'))
            @foreach(collect(session()->pull('info')) as $message)
                @if(!\Illuminate\Support\Facades\Session::has(base64_encode($message)) and $message != '')
                    <div data-key="{{base64_encode($message)}}" class="alert alert-info alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        {!! $message !!}
                    </div>
                @endif
            @endforeach
        @endif
    </div>
@endif