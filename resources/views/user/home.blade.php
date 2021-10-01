@extends("user.layouts.main")

@section("css")
@endsection

@section("content")
    <!-- BANNER -->
	<div id="oc-fullslider" class="banner owl-carousel">
        @foreach($slides as $slide)
            <div class="owl-slide">
                <div class="item">
                    <img src="{{ asset('public/files/slides/' . $slide->file_name) }}" alt="Slider">
                    <div class="slider-pos">
                        <div class="container">
                            <div class="wrap-caption">
                                <h1 class="caption-heading">{{ $slide->title }}</h1>
                                <p>{{ $slide->sub_title }}</p>
                                <a href="#" class="btn btn-primary">Learn More</a>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="clearfix"></div>

    <!-- PRICING TABLES -->
    <div id="pricing-table">
        <div class="content-wrap">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <h2 class="section-heading center no-after mb-5">
                            Pricing Tables
                        </h2>
                        <p class="subheading text-center">There will be some text.</p>
                    </div>
                </div>

                <div class="row">
                    @foreach($memberPlanTypes as $plan)
                        <!-- Plan Item -->
                        <div class="col-12 col-md-4">
                            <div class="rs-pricing-1 bg-primary-1 text-white mb-5"
                                onclick="window.location.href='{{ route('user.priceterms', ['plan_type' => $plan->id]) }}'">
                                <h3 class="title">{{ $plan->name }}</h3>
                                <div class="price">
                                    <div>
                                        <span class="unit">$</span>{{ $plan->adult_price }}
                                        <span class="unit mt-3"> mo / adult</span>
                                    </div>
                                    <div class="price-year">
                                        <span class="unit">$</span>{{ $plan->adult_price * 12 }}
                                        <span class="unit mt-3"> year / adult</span>
                                    </div>
                                    
                                    <div class="mt-1">
                                        <span class="unit">$</span>{{ $plan->child_price }}
                                        <span class="unit mt-3"> mo / child</span>
                                    </div>
                                    <div class="price-year">
                                        <span class="unit">$</span>{{ $plan->child_price * 12 }}
                                        <span class="unit mt-3"> year / child</span>
                                    </div>
                                </div>
                                <div class="features">
                                    <ul>
                                        <li>{{$plan->desc_first}}</li>
                                        <li>{{$plan->desc_second}}</li>
                                        <li>{{$plan->desc_third}}</li>
                                    </ul>
                                </div>
                                <div class="action">
                                    <a href="{{ route('user.priceterms', ['plan_type' => $plan->id]) }}" class="btn btn-secondary">Learn More</a>
                                </div>
                            </div>
                        </div>
                        <!--./ Plan Item -->
                    @endforeach
                    <div class="offset-md-3 col-md-6">
                        <a href="{{ route('user.auth.showSignup') }}">
                            <button type="button" class="w-100 btn btn-primary"><h3>Ready to signup!</h3></button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- vision start -->
    <div id="about-us">
        <div class="content-wrap">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <img src="{{ asset('public/files/contents/' . $bodyContents[0]->image) }}" alt="" class="img-fluid img-content">
                    </div>
                    <div class="col-12 col-md-6">
                        <h2 class="text-primary mb-3">Our Mission</h2>
                        <p class="mb-5">{!! $bodyContents[0]->content !!}</p>
                        <a href="#" class="btn btn-primary">Learn More</a>
                    </div>					
                </div>
            </div>
        </div>
    </div>
    <!--./ vision end-->

    <!-- Mission start -->
    <div id="about-us-2">
        <div class="content-wrap">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <h2 class="text-primary mb-3">Our Vision</h2>
                        <p class="mb-5">{!! $bodyContents[1]->content !!}</p>
                        <a href="#" class="btn btn-primary" style="margin-bottom: 50px">Learn More</a>
                    </div>
                    <div class="col-12 col-md-6">
                        <img src="{{ asset('public/files/contents/' . $bodyContents[1]->image) }}" alt="" class="img-fluid img-content">
                    </div>					
                </div>
            </div>
        </div>
    </div>
    <!-- ./ Mission end -->
    <!-- Values start -->
    <div id="about-us-2">
        <div class="content-wrap">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <img src="{{ asset('public/files/contents/' . $bodyContents[2]->image) }}" alt="" class="img-fluid img-content">
                    </div>
                    <div class="col-12 col-md-6">
                        <h2 class="text-primary mb-3">Our Values</h2>
                        <p class="mb-5">{!! $bodyContents[2]->content !!}</p>
                        <a href="#" class="btn btn-primary">Learn More</a>
                    </div>					
                </div>
            </div>
        </div>
    </div>
    <!--./ values end -->

    <!-- TESTIMONIALS start -->
    <div id="testimonials" class="bg-gray">
        <div class="content-wrap">
            <div class="container">

                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <h2 class="section-heading center no-after mb-5">
                            Testimonials
                        </h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-12">
                        <div id="caro-2">
                            @foreach($testmonials as $testmonial)
                                <!-- Item 1 start -->
                                <div class="item">
                                    <div class="rs-testimonial-2">
                                        <div class="body">
                                            <p class="font__color-white"><em>{!! $testmonial->content !!}</em> </p>
                                        </div>
                                        <div class="media">
                                            <img src="{{ asset('public/files/testmonials/' . $testmonial->image) }}" alt="" class="rounded-circle">
                                        </div>
                                        <div class="rs-testimonial-footer">
                                            {{ $testmonial->owner }} <cite title="Source Title">{{ $testmonial->description }}</cite>
                                        </div>
                                    </div>
                                </div>
                                <!--./ Item 1 end -->
                            @endforeach                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--./ TESTIMONIALS end -->
@endsection

@section("js")

@endsection