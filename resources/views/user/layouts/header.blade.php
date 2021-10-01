@php
    $isLoggedIn = Auth::check();
@endphp
<style>
</style>

<!-- HEADER start -->
<div class="header header-2">
    <!-- TOPBAR start -->
    <div class="topbar d-none d-md-block">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-3 col-md-3">
                    <div class="rs-icon-info no-bg mb-0">
                        <div class="info-icon">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <div class="info-text">{{ $contactInfo->email }}</div>
                    </div>
                </div>
                <div class="col-3 col-md-3">
                    <div class="rs-icon-info no-bg mb-0">
                        <div class="info-icon">
                            <i class="fa fa-phone"></i>
                        </div>
                        <div class="info-text">{{ $contactInfo->phone }}</div>
                    </div>
                </div>
                <div class="col-3 col-md-3">
                    <div class="sosmed-icon no-bg-hover float-right d-inline-flex">
                        <a href="{{ $contactInfo->facebook }}" target="_blank"><i class="fa fa-facebook"></i></a> 
                        <a href="{{ $contactInfo->twitter }}" target="_blank"><i class="fa fa-twitter"></i></a> 
                        <a href="{{ $contactInfo->instagram }}" target="_blank"><i class="fa fa-instagram"></i></a> 
                        <a href="{{ $contactInfo->pinterest }}" target="_blank"><i class="fa fa-pinterest"></i></a> 
                    </div>
                </div>
                <div class="col-3 col-md-3">
                    <div class="rs-icon-info no-bg mb-0">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--./ TOPBAR end -->

    <!-- NAVBAR SECTION -->
    <div class="navbar-main">
        <div class="container">
            <nav id="navbar-example" class="navbar navbar-expand-lg">
                <a class="wastina_logo_new" href="{{ route('home') }}">
                    <img src="https://www.wastina.com/wp-content/uploads/2018/06/wastina_logo_new.png" style="margin-top:10px;height:100px; width:300px;"alt="" />
                </a>
                @if(Route::currentRouteName() != 'user.priceterms' && Route::currentRouteName() != 'user.contactus')
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNavDropdown">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item active">
                                <a class="nav-link" href="#oc-fullslider">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#pricing-table">Membership Plans</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#about-us">About Us</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#testimonials">Testimonials</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" onclick="window.location = '{{ route('user.contactus') }}'">Contact Us</a>
                            </li>
                            @if($isLoggedIn)
                                <li class="nav-item">
                                    <a class="nav-link" href="#" onclick="window.location = '{{ route('user.auth.logout') }}'">Log out</a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link" href="#" onclick="window.location = '{{ route('user.auth.showLogin') }}'">Login</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                @endif
            </nav>
        </div>
    </div>
    <!--./ NAVBAR SECTION end -->
</div>
<!--./ HEADER end -->