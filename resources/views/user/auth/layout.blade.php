<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Healthcare Membership</title>
        <!-- Meta-Tags -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
        <meta name="keywords" content="">
        <script>
            addEventListener("load", function () {
                setTimeout(hideURLbar, 0);
            }, false);
    
            function hideURLbar() {
                window.scrollTo(0, 1);
            }
        </script>
        <!-- //Meta-Tags -->
        <!-- Index-Page-CSS -->
        <link rel="stylesheet" type="text/css" href="{{ asset('public/template/css/vendor/bootstrap.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('public/template/css/vendor/font-awesome.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('public/template/css/style1.css') }}" type="text/css" media="all">
        <link rel="stylesheet" href="{{ asset('public/template/css/custom.css') }}" type="text/css" media="all">
        <style>
            .invalid-feedback {
                display: block;
            }
        </style>
    </head>

    <body>
        <header>
            <div class="text-center">
                <a class="wastina_logo_new " href="{{ route('home') }}">
                    <img src="https://www.wastina.com/wp-content/uploads/2018/06/wastina_logo_new.png" style="margin-top:10px;height:100px; width:300px;"alt="" />
                </a>
            </div>
        </header>

        <!-- //header -->
        <section class="{{ $formType }}">
            <div class="main_w3agile">
                @yield("content")
            </div>
        </section>
        
        <script src="{{ asset('public/template/js/vendor/jquery.min.js') }}"></script>
        <script src="{{ asset('public/template/js/vendor/bootstrap.min.js') }}"></script>
        
        @yield('js')
    </body>
</html>
            