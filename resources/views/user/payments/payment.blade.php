@php
    $stripeMonth = old('stripe_month', now()->month);
@endphp
@extends("user.layouts.page_layout")

@section('title', 'Payment')

@section("css")
@endsection

@section("page_content")

    <form method="POST" action="{{ route('user.payments.stripe') }}" class="appointment-form ftco-animate">
        @csrf
        @if (session('payment_error'))
            <div class="alert alert-danger">
                {{ session('payment_error') }}
            </div>
        @endif
        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    <input type="text" class="form-control{{ $errors->has('stripe_number') ? ' is-invalid' : '' }}"
                        name="stripe_number" value="{{ old('stripe_number') }}" placeholder="Credit Card Number" required />
                    @if ($errors->has('stripe_number'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('stripe_number') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <select name="stripe_month" class="form-control" required>
                            @for($i=1; $i<=12; $i++)
                                <option value="{{ $i }}"{{ $stripeMonth==$i ? ' selected' : ''}}>{{ $i }}</option>
                            @endfor
                        </select>
                        @if ($errors->has('stripe_month'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('stripe_month') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-4">
                        <select name="stripe_year" class="form-control" required>
                            @for($i=2019; $i<=2100; $i++)
                                <option value="{{ $i }}"{{ old('stripe_year')==$i ? ' selected' : ''}}>{{ $i }}</option>
                            @endfor
                        </select>
                        @if ($errors->has('stripe_year'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('stripe_year') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-4" >
                        <input type="text" class="form-control{{ $errors->has('stripe_cvc') ? ' is-invalid' : '' }}"
                            name="stripe_cvc" value="{{ old('stripe_cvc') }}" placeholder="CVC" required />
                        @if ($errors->has('stripe_cvc'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('stripe_cvc') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control{{ $errors->has('stripe_name') ? ' is-invalid' : '' }}"
                        name="stripe_name" value="{{ old('stripe_name') }}" placeholder="Name on Card" required />
                    @if ($errors->has('stripe_name'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('stripe_name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-md-6 ml-md-4">
                <div class="d-md-flex ml-md-4">
                    <a href="#" onclick="confirmTerm(event, '{{ route('user.payments.paypal') }}')">
                        <img alt="Checkout with PayPal" style="width:190px;height:120px;" src="{{ asset('public/template/images/paypal.png') }}" />
                    </a>
                </div>
                <button type="button" class="btn btn-secondary" onclick="confirmTerm(event, '{{ route('user.payments.cache') }}')"
                    style="width:200px;height:50px;margin-left:20px;margin-top:10px;">
                    Offline Checkout
                </button>
            </div>
        </div>
        
        <br />
        <div class="d-md-flex">
            <div class="custom-control custom-checkbox" style="">
                <input type="checkbox" class="custom-control-input" id="terms-check">
                <label class="custom-control-label" for="terms-check">I Accept the 
                    <a href="{{ route('payment.terms') }}" target="_blank" style="text-decoration:underline">
                        Terms & Conditions of Wastina.com
                    </a>
                </label>
            </div>
        </div>
        <br />
        <div class="d-md-flex">
            <input type="submit" value="Checkout" class="btn btn-secondary" style="width:170px;height:50px;margin-left:5px;margin-top:10px;">
            <a href="{{ route('user.membership.plans') }}">
                <input type="button" value="Cancel" class="btn btn-danger" style="width:170px;height:50px;margin-left:20px;margin-top:10px;">
            </a>
        </div>
    </form>
@endsection

@section("js")
<script>
    $(function(){
        $('form').on('submit', function(e){
            e.preventDefault();
            if ($('#terms-check').is(':checked')) {
                this.submit();
            } else {
                alert('Please check Terms and Conditions');
            }            
        });
    });

    function confirmTerm(e, url) {
        e.preventDefault();
        if ($('#terms-check').is(':checked')) {
            window.location.href = url;
        } else {
            alert('Please check Terms and Conditions');
        }     
    }
</script>
@endsection