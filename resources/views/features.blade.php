<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <title>Slack forwarder</title>
</head>

<body>
    <div class="wrapper feature-page">
        <div class="header">
            <img src="{{asset('img/logo.png')}}" alt="">
            <div class="content-header">
                @if(Auth::guest())
                <a data-toggle="modal" href='#modal-login' class="btn btn-default">Login</a>
                @else
                @admin
                <a href="admin/dashboard" class="btn btn-default">Go to app</a>
                @else
                <a href="/dashboard" class="btn btn-default">Go to app</a>
                @endadmin
                @endif
            </div>
        </div>

        <div class="container mt-10 markdown-content">
            {{ $html }}
        </div>

        <div class="footer">
            <div class="content-footer">
                <p><span>Made by </span><a href="https://research.sun-asterisk.com/en"><img src="{{asset('img/Sun-logo.png')}}" alt=""></a> <span>R&D Lab</span></p>
                <p><a href="/features" target="_blank">Features</a></p>
                <p>We &#9829; Open Source</p>
                <p>This service is open-sourced at <a href="https://github.com/sun-asterisk-research/chatwork-forwarder/tree/slack"><img src="{{asset('img/github.png')}}" alt=""></a></p>
            </div>
        </div>

        <img class="footer-logo" src="{{asset('img/cw-logo-footer.png')}}" alt="">
    </div>

    <div class="modal fade" id="modal-login">
        <div class="modal-dialog">
            <div class="login-form">
                {{ Form::open(['method' => 'POST', 'id' => 'login_form']) }}
                @csrf
                <div class="login_title">
                    <h1>Login</h1>
                </div>

                <div class="form-group">
                    <div class="has-error" id="error-email"></div>
                    <label for="uname"><b>Email</b></label>
                    <input id="email" placeholder="Enter email" type="email" class="form-control mb-5" name="email" value="{{ old('email') }}" autocomplete="email" autofocus>

                    <label for="psw"><b>Password</b></label>
                    <input id="password" placeholder="Enter password" type="password" class="form-control" name="password" autocomplete="current-password">
                    <div class="has-error" id="error-password"></div>

                    <label>
                        <input type="checkbox" checked="checked" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }}
                    </label>

                    <a class="float_right" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>

                    <button type="submit" class="btn btn-primary">{{ __('Login') }}</button>
                    <a href="/redirect" class="google btn"><i class="fa fa-google fa-fw"></i> Login with Google+</a>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/login.js') }}"></script>
</body>

</html>
