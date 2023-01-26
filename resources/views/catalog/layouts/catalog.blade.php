<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <link rel="stylesheet" href={{ asset("css/bootstrap.min.css") }}>
        <link rel="stylesheet" href={{ asset("fontawesome/css/all.min.css") }}>
        <link rel="stylesheet" href={{ asset("css/templatemo-style.css") }}>

        @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    </head>
    <body>
    <!-- Page Loader -->
    <div id="loader-wrapper">
        <div id="loader"></div>

        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>

    </div>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-film mr-2"></i>
                Catalog-Z
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link nav-link-1 active" aria-current="page" href="index.html">Photos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-2" href="videos.html">Videos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-3" href="about.html">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-4" href="contact.html">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="tm-hero d-flex justify-content-center align-items-center" data-parallax="scroll" data-image-src="img/hero.jpg">

    </div>

    <div class="container-fluid tm-container-content tm-mt-60">
    @yield('content')
    </div>
    <footer class="tm-bg-gray pt-5 pb-3 tm-text-gray tm-footer" style="position: absolute; bottom: 0;
  width: 100%;">
        <div class="container-fluid tm-container-small">

        </div>
    </footer>

    <script src={{ asset("js/plugins.js") }}></script>
    <script type="module">
        $(window).on("load", function() {
            $('body').addClass('loaded');
            console.log("loaded");
        });
    </script>
    </body>
</html>
