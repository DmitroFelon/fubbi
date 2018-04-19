@extends('master')

@section('before-content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-4">
            <h2>Login</h2>
        </div>
    </div>
@endsection

@section('content')
    <div class="ibox-content">
        <form class="form-horizontal" method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="col-md-4 control-label">{{_i('E-Mail Address')}}</label>

                <div class="col-md-6">
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required
                           autofocus>

                    @if ($errors->has('email'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password" class="col-md-4 control-label">{{_i('Password')}}</label>

                <div class="col-md-6">
                    <input id="password" type="password" class="form-control" name="password" required>

                    @if ($errors->has('password'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                    <div class="">
                        <div class="i-checks">
                            <label>
                                <input class="keywords-checkbox"
                                       {{ old('remember') ? 'checked' : '' }}
                                       type="checkbox"
                                       name="remember"> <i></i>
                                {{_i('Remember Me')}}
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-8 col-md-offset-4">
                    <button type="submit" class="btn btn-primary">
                        {{_i('Login')}}
                    </button>

                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        {{_i('Forgot Your Password?')}}
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection
