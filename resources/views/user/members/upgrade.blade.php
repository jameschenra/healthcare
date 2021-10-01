@php
    $planTypeId = $membership->plan_type_id;
    if($membership->status == 4) {
        $planTypeId = 0;
    }
@endphp
@extends("user.layouts.page_layout")

@section('title', 'Upgrade Membership')

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
            <h3 class="text-center">
                @if($membership->status == 3)
                    This member({{ $membership->user->relationship }}) purchased "{{$membership->planType->name}}" plan.
                @elseif($membership->status == 4)
                    This member({{ $membership->user->relationship }}) has been expired.
                @endif
            </h3>
            <br />
            
            <div class="row">
                @foreach($planTypes as $plan)
                    <!-- Plan Item -->
                    <div class="col-12 col-md-4">
                        <div class="rs-pricing-1 bg-primary-1 text-white mb-5{{ $plan->id == $planTypeId ? ' active' : '' }}"
                            data-value="{{ $plan->id }}">
                            <h3 class="title">{{ $plan->name }}</h3>
                            <div class="price">
                                <div>
                                    <span class="unit">$</span>{{ $membership->user->is_adult == 1 ? $plan->adult_price : $plan->child_price }}
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
                                <form method="POST" action="{{ route('user.members.upgrade') }}">
                                    @csrf
                                    
                                    <input type="hidden" name="membership_id" value="{{ $membership->id }}" />
                                    <input type="hidden" name="plan_type" value="{{ $plan->id }}" />
                                    <input type="hidden" name="update_type" value="{{ $updateType }}" />

                                    <button type="submit" class="btn btn-secondary{{$plan->id <= $planTypeId ? ' isDisabled' : ''}}">
                                        Select
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--./ Plan Item -->
                @endforeach
            </div>

            <div class="text-center">
                <a href="{{ route('user.members.index') }}"><button type="button" class="btn btn-secondary">Return to list</button></a>
            </div>
        </div>
    </div>
@endsection

@section("js")
<script>
    $(function(){
        $('.rs-pricing-1').on('click', function(){
            var $submitForm = $(this).find('form');
            var $submitButton = $(this).find('form button[type=submit]');
            if (!($submitButton.hasClass('isDisabled'))) {
                $submitForm.submit();
            }
        });
    });
</script>
@endsection