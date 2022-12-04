<!doctype html>
<html lang="{{ str_replace('_', '_', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/splide.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

</head>
<body class="bg-light">
@include('app.nav')
@include('app.alert')
@yield('content')

<div id="preloader"></div>
<div id="scrollToTop" class="py-1 px-2 rounded-circle"><i class="bi bi-arrow-up-short text-bg-primary"></i></div>
<script>
    const scrollToTop = document.querySelector("#scrollToTop");
    window.addEventListener("scroll", scrollFunction);

    function scrollFunction() {
        if(window.pageYOffset > 300){
            scrollToTop.style.display = "flex";
        }
        else {
            scrollToTop.style.display = "none";
        }
    }

    scrollToTop.addEventListener("click", backToTop);

    function backToTop() {
        window.scrollTo(0, 0);
    }
</script>
<script>
    let loader = document.getElementById('preloader');

    setTimeout(() => {
        window.onload = loader.style.display = 'none';
    }, 600);

    console.log(loader);
</script>

<script type="text/javascript" src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/splide.min.js') }}"></script>
</body>
</html>