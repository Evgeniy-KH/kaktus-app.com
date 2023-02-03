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
    <!-- Font Awesome -->
    <link rel="stylesheet" href={{ asset("plugins/fontawesome-free/css/all.min.css") }}>
    <!-- Ionicons -->
    <link rel="stylesheet" href={{ asset("https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css") }}>
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href={{ asset("plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css") }}>
    <!-- iCheck -->
    <link rel="stylesheet" href={{ asset("plugins/icheck-bootstrap/icheck-bootstrap.min.css") }}>
    <!-- Theme style -->
    <link rel="stylesheet" href={{ asset("dist/css/adminlte.min.css") }}>
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href={{ asset("plugins/overlayScrollbars/css/OverlayScrollbars.min.css") }}>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <div id="app">

        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto mb-2 mb-lg-0">
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
                                <img class="image rounded-circle"
                                     src="{{asset('/storage/images/'.Auth::user()->image)}}"
                                     alt="profile_image" style="width: 80px;height: 80px; padding: 10px; margin: 0px; ">
                            @endif
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle nav-link-3" role="button" data-bs-toggle="dropdown"
                                   aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" id="personal-edit" data-id="{{ Auth::user()->id }}"
                                       href="personal/{{ Auth::user()->id }}/edit">
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
            <form class="d-flex tm-search-form" id="search-form">
                <input class="form-control tm-search-input form-control-sidebar" type="search" placeholder="Search" aria-label="Search"
                       style="
    width: 360px;
    border-radius: 0;
    padding: 12px 15px;
    color: #009999;
     height: 50px;
    border: none;"
                >
                <button class="btn btn-outline-success tm-search-btn" type="submit" style="color: white;
    background-color: #009999;
    border: none;
    width: 100px;
    height: 50px;
    margin-left: -1px;">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
        < <!-- Main Sidebar Container -->
        <aside class="main-sidebar tm-bg-gray elevation-2" style=" width: 200px">
            <!-- Sidebar -->
            <div class="sidebar">
{{--                <!-- SidebarSearch Form -->--}}
{{--                <div class="form-inline mt-3">--}}
{{--                    <div class="input-group" data-widget="sidebar-search">--}}
{{--                        <input class="form-control form-control-sidebar" type="search" placeholder="Search"--}}
{{--                               aria-label="Search">--}}
{{--                        <div class="input-group-append">--}}
{{--                            <button class="btn btn-sidebar">--}}
{{--                                <i class="fas fa-search fa-fw"></i>--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="/home" class="nav-link">
                                <i class="nav-icon fas fa-th"></i>
                                <p class="tm-text-primary">
                                    Home
                                </p>
                            </a>
                        </li>
                        <!-- Add icons to the links using the .nav-icon class
                             with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon far fa-plus-square"></i>
                                <p class="tm-text-primary">
                                    Recipes
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="/personal/dish/create" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Create</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="pages/mailbox/read-mail.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Read</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
        <!-- Main content -->
        <div class="content-wrapper" style="background-color: white">
            <section class="content">
                <div class="container-fluid tm-container-content tm-mt-60">
                    @yield('content')
                </div>
            </section>
        </div>
        <footer class="main-footer" style="margin-left: 200px;">
            <strong>Created by TatianaKhr <a href="https://github.com/TatianaKhr">GitHub</a>.</strong>
            <div class="float-right d-none d-sm-inline-block">
                <b>2022</b>
            </div>
        </footer>
        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

    </div>


</div>
</body>

<script src={{ asset("js/plugins.js") }}></script>
<script type="text/javascript" src="{{ asset('js/jquery-3.6.3.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/moment.js') }}"></script>
<script type="text/javascript" src="{{ asset('bootstrap-5.2.3-dist/js/bootstrap.bundle.js') }}"></script>
<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<!-- AdminLTE App -->
<script src={{ asset("dist/js/adminlte.js") }}></script>


</html>
