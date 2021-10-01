@extends("user.layouts.main")

@section("content")
<!-- page title start -->
<section class="hero-wrap hero-wrap-2" style="background-color:#A9A9A9;" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
            <div class="col-md-9 ftco-animate text-center">
                <h1 class="mb-2 bread">@yield('title')</h1>
            </div>
        </div>
    </div>
</section>
<!--./ page title start -->

<br />
<section class="ftco-section ftco-no-pt ftco-no-pb content-section">
    <div class="container">
        @yield('page_content')
    </div>
</section>
<br />
@endsection