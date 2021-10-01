@php
    $currentMembership = $membership ? $membership->plan_type_id : 0;
@endphp
@extends("user.layouts.page_layout")

@section('title', 'Profile Edit')

@section("css")
    <style>
        .container {
            max-width: 1500px;
        }
    </style>
@endsection

@section("page_content")
    <div class="row d-flex align-items-stretch no-gutters">
        <div class="col-md-12 p-4 p-md-5 p-md-0 order-md-last bg-light">
            @if($membership)
                <h3 class="text-center">You have already purchased "{{$membership->planType->name}}" plan.</h3>
            @else
                <h3 class="text-center">Please select your plan.</h3>
            @endif
            <br />
            
            <div class="row">
                @foreach($planTypes as $plan)
                    <!-- Plan Item -->
                    <div class="col-12 col-md-3">
                        <div class="rs-pricing-1 bg-primary-1 text-white mb-5{{ $plan->id == $currentMembership ? ' active' : '' }}
                                {{$currentMembership != 0 ? ' isDisabled' : ''}}"
                            data-value="{{ $plan->id }}"
                            onclick="window.location.href='{{ route('user.membership.signup', [$plan->id]) }}'">
                            <h3 class="title">{{ $plan->name }}</h3>
                            <div class="price">
                                <div>
                                    <span class="unit">$</span>{{ $plan->adult_price }}
                                    <span class="unit mt-3"> /mo </span>
                                </div>
                            </div>
                            <div class="features">
                                <ul>
                                    <li>{{ $plan->desc_first }}</li>
                                    <li>{{ $plan->desc_second }}</li>
                                    <li>{{ $plan->desc_third }}</li>
                                </ul>
                            </div>
                            <div class="action">
                                <a href="{{ route('user.membership.signup', [$plan->id]) }}"
                                    class="btn btn-secondary{{$currentMembership != 0 ? ' isDisabled' : ''}}">Select</a>
                            </div>
                        </div>
                    </div>
                    <!--./ Plan Item -->
                @endforeach
                <div class="col-12 col-md-3">
                    <div class="rs-pricing-1 text-white mb-5{{ $currentMembership == 0 ? ' active' : '' }}"
                        data-value="0" style="background-color: #888">
                        <h3 class="title">No Plan</h3>
                        <div class="price">
                            <div>
                                <span class="unit">$</span>0
                                <span class="unit mt-3"> mo </span>
                            </div>
                        </div>
                        <div class="features">
                            <ul>
                                <li>Ony Manage membership</li>
                                <li>&nbsp;</li>
                                <li>&nbsp;</li>
                            </ul>
                            <div class="action">
                                <a href="{{ route('user.membership.signup', [0]) }}"
                                    class="btn btn-secondary{{$currentMembership != 0 ? ' isDisabled' : ''}}">Select</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("js")
    <script>
        /* var signupUrl = "{{route('user.membership.signup', [0])}}";
        var currentMembership = {!! $currentMembership !!};

        $(function() {
            $('.rs-pricing-1').on('click', function(){
                $('.rs-pricing-1').removeClass('active');
                $(this).addClass('active');
                currentMembership = $(this).data('value');
            });

            $('#btn-signup').on('click', function(){
                signupUrl = signupUrl.replace(/\/[^\/]*$/, '/' + currentMembership);
                window.location.href = signupUrl;
            });
        }); */
    </script>
@endsection