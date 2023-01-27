<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href={{ asset("css/bootstrap.min.css") }}>
    <link rel="stylesheet" href={{ asset("fontawesome/css/all.min.css") }}>
    <link rel="stylesheet" href={{ asset("css/templatemo-style.css") }}>


    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto ms-auto">
                    @guest

                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link nav-link-3" href="{{ route('login') }}">Login</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link nav-link-3" href="{{ route('register') }}">Register</a>
                            </li>
                        @endif

                    @endguest

                    @if (auth()->user())
                            @if(Auth::user()->image)
                                <img class="image rounded-circle" src="{{asset('/storage/images/'.Auth::user()->image)}}" alt="profile_image" style="width: 80px;height: 80px; padding: 10px; margin: 0px; ">
                            @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle nav-link-3" role="button" data-bs-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" id="personal-edit" data-id="{{ Auth::user()->id }}"
                                   href="personal/{{ Auth::user()->id }}/edit ">
                                    {{ __('Edit') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>


                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    <div class="tm-hero d-flex justify-content-center align-items-center" data-parallax="scroll"
         data-image-src={{ asset("img/hero.jpg") }}>
    </div>
    <div class="container-fluid tm-container-content tm-mt-60">
        @yield('content')
    </div>

    <footer class="ttm-bg-gray pt-5 pb-3 tm-text-gray tm-footer">
        <div class="container-fluid tm-container-small">
        </div>
    </footer>
</body>

<script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>

<script src={{ asset("js/plugins.js") }}></script>


</html>
