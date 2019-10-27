<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} @yield('heading')</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css"/>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png"/>
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}"/>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.css"/>
    @yield('head')
</head>
<body>
@yield('body')
<button onclick="topFunction()" id="goToTop" title="Go to top">Top</button>
<script type="text/javascript">
    $(function () {
        $('.datetimepicker-input').datetimepicker({
            showToday: true,
            showClear: true,
            showClose: true,
            keepOpen: false
        });
        $('.date').datetimepicker({
            showToday: true,
            showClear: true,
            showClose: true,
            keepOpen: false
        });
    });

    window.onscroll = function () {
        scrollFunction()
    };

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            document.getElementById("goToTop").style.display = "block";
        } else {
            document.getElementById("goToTop").style.display = "none";
        }
    }

    // When the user clicks on the button, scroll to the top of the document
    function topFunction() {
        $('html,body').animate({ scrollTop: 0 }, 'slow');
    }
</script>
@yield('scripts')
</body>
</html>
