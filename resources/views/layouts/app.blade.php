<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700;1,900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Source Sans Pro', sans-serif;
        }
        #mainForm {
            font-size: 12px!important;
        }
        .btn-inspect {
            display: flex;
            align-items: center;
        }
        .btn-part-complete {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .btn-part-complete button {
            padding: 5px;
            font-size: 12px;
            font-weight: bold;
            color: white;
            text-align: center;
            width: 125px;
            height: 30px;
            border-radius: 20px;
            border-color: black;
            background: rgb(1, 113, 188);
        }
        .btn-next-measurement {
            width: 108px;
            height: 24px;
            font-family: "Source Sans Pro";
            font-size: 11px;
            color: rgb(0, 0, 0);
            border-radius: 20px;
            padding: 0;
            background-color: #e0e0e0;
        }
        .btn-inspect .btn{
            background: rgb(224, 224, 224);
            width: 132px;
            height: 26px;
            line-height: 25px;
            font-family: "Source Sans Pro";
            font-size: 12px;
            color: rgb(0, 0, 0);
            border-radius: 20px;
            padding: 0;
        }
        .btn-next-measurement {
            bottom: 0.5rem;
        }
        #mainForm .card {
            border: 1px solid black;
        }
        #mainForm .card-header {
            font-weight: bold;
            text-align: center;
            padding: 0 10px;
            background-color: #bdbdbd;
            line-height: 32px;
            cursor: pointer;
            font-size: 12px;
            font-family: "Source Sans Pro";
            color: black;
        }
        #mainForm .card-body {
            padding: 16px 1.25rem
        }
        #mainForm .form-group{
            text-align: center;
            margin-bottom: 6px;
        }

        #mainForm .form-group label{
            margin-bottom: 0.3rem;
        }

        #mainForm .form-group input {
            text-align: center;
            height: 21px;
            padding: 0;
        }
        #mainForm .form-group select {
            text-align: center;
            text-align-last: center;
            -moz-text-align-last: center;
            height: 21px;
            padding: 0;
        }
        #mainForm .form-group input, #mainForm .form-group select {
            border-color: black;
            border-radius: 0;
            font-size: 12px;
        }
        #accordion .card-body p {
            width: fit-content;
            margin-bottom: 6px;
        }
        #mainForm .divider {
            width: 1px;
            background-color: gray;
            margin: 22px 0 6px;
        }
        #hose_measured_len_2 {
            margin-bottom: 27px;
        }
        .measured-len-group {
            position: absolute;
            bottom: 0;
            width: calc(100% - 30px);
        }

        #conversionCardBody {
            padding: 20px 0 !important;
        }
        #conversionCardBody .wrapper {
            width: fit-content;
            margin: auto;
        }

        @media (max-width: 768px) {
            .measured-len-group {
                position: relative;
                width: calc(100%);
            }

            .btn-inspect {
                margin: 30px 0;
                align-items: center;
                flex-direction: column;
            }

            .btn-part-complete{
                margin-bottom: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div id="app">
        @guest
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm mb-5">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        @endguest

        <main>
            @yield('content')
        </main>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    @yield('scripts')
</body>
</html>
