@php
    $isLoggedIn = Auth::check();
@endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">

        <!--[if IE]><meta http-equiv="x-ua-compatible" content="IE=9" /><![endif]-->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Healthcare Membership</title>

        <meta name="description" content="This is healthcare membership site written by joker">
        <meta name="keywords" content="healthcare membership">
        <meta name="author" content="joker">

        <!-- fav icon -->
        <link rel="shortcut icon" href="{{ asset('public/template/images/favicon.ico') }}" />
        <link rel="apple-touch-icon" href="{{ asset('public/template/images/apple-touch-icon.png') }}" />
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('public/template/images/apple-touch-icon-72x72.png') }}" />
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('public/template/images/apple-touch-icon-114x114.png') }}" />

        <!-- vendor styles -->
        <link rel="stylesheet" type="text/css" href="{{ asset('public/template/css/vendor/bootstrap.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('public/template/css/vendor/font-awesome.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('public/template/css/vendor/owl.carousel.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('public/template/css/vendor/owl.theme.default.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('public/template/css/vendor/magnific-popup.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('public/template/css/vendor/animate.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('public/template/css/vendor/bootstrap-dropdownhover.min.css') }}" />

        <!-- custom styles -->
        <link rel="stylesheet" type="text/css" href="{{ asset('public/template/css/style.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('public/template/css/custom.css') }}" />

        @yield("css")
    </head>
    <body @if(!$isLoggedIn) data-spy="scroll" data-target="#navbar-example" @endif>
        <!-- LOAD PAGE -->
	    <div class="animationload">
            <div class="loader"></div>
        </div>

        <!-- BACK TO TOP SECTION -->
        <a href="#0" class="cd-top cd-is-visible cd-fade-out">Top</a>

        @if($isLoggedIn)
            @include("user.layouts.header_loggedin")
        @else
            @include("user.layouts.header")
        @endif
        

        @yield("content")

        @include("user.layouts.footer")

        <!-- JS VENDOR -->
        <script src="{{ asset('public/template/js/vendor/jquery.min.js') }}"></script>
        <script src="{{ asset('public/template/js/vendor/bootstrap.min.js') }}"></script>
        <script src="{{ asset('public/template/js/vendor/owl.carousel.js') }}"></script>
        <script src="{{ asset('public/template/js/vendor/jquery.magnific-popup.min.js') }}"></script>

        <!-- SENDMAIL -->
        <script src="{{ asset('public/template/js/vendor/validator.min.js') }}"></script>
        <script src="{{ asset('public/template/js/vendor/form-scripts.js') }}"></script>

        <script src="{{ asset('public/template/js/script.js') }}"></script>

        @yield("js")
    </body>
</html>