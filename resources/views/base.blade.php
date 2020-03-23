<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 2.5em;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="container" id="app">
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ url('/home') }}">Home</a>
            @else
                <a href="{{ route('login') }}">Login</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Register</a>
                @endif
            @endauth
        </div>
    @endif

    @yield('content')
</div>
<script>
    const PUSHER_APP_KEY = "{{ config('broadcasting.connections.pusher.key') }}";
    const PUSHER_APP_CLUSTER = "{{ config('broadcasting.connections.pusher.options.cluster') }}";
    const DEFAULT_PROGRESS_BAR_OPTIONS = {
        text: {
            color: '#FFFFFF',
            shadowEnable: true,
            shadowColor: '#000000',
            fontSize: 10,
            fontFamily: 'Helvetica',
            dynamicPosition: false,
            hideText: false
        },
        progress: {
            color: '#2dbd2d',
            backgroundColor: '#333333'
        },
        layout: {
            height: 20,
            width: 140,
            verticalTextAlign: 61,
            horizontalTextAlign: 43,
            zeroOffset: 0,
            strokeWidth: 30,
            progressPadding: 0,
            type: 'line'
        }
    };
</script>
<script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>
