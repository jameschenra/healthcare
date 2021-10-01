@php
	$stripeMonth = old('stripe_month', now()->month);
@endphp

@extends("admin.layouts.layout")
@section("title","Payment") 
@section("subtitle","Checkout") 

@section("css")
@endsection
 
@section("breadcumps")
  <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
  <li><a href="{{route('admin.users')}}">Users</a></li>
  <li class="active">Payment/Chekcout</li>
@endsection
 
@section("content")
<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <!-- Default box -->
    <a href="{{route('admin.users')}}">
      <i class="fa fa-angle-double-left"></i> Back to all  <span class="text-lowercase">users</span>
    </a><br /><br />

		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Payment Checkout</h3>
			</div>
			
			<!-- box body -->
			<div class="box-body" style="padding: 15px;">
				@if (session('payment_error'))
					<div class="alert alert-danger">
						{{ session('payment_error') }}
					</div>
				@endif
		  
				<div class="row">
					<!-- stripe form -->
					<div class="col-md-5">
						<form method="POST" action="{{route('admin.payment.stripe')}}" accept-charset="UTF-8" autocomplete="off" id="user-form">

							@csrf
							
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
								<input type="submit" value="Checkout" class="btn btn-success" style="width:170px;height:50px;margin-left:5px;margin-top:10px;">
								<a href="{{ route('admin.users') }}">
									<input type="button" value="Cancel" class="btn btn-danger" style="width:170px;height:50px;margin-left:20px;margin-top:10px;">
								</a>
							</div>
						</form>
					</div>
					<!--./ stripe form -->

					<!-- paypal and offline -->
					<div class="col-md-6 ml-md-4">
						<div class="d-md-flex ml-md-4">
							<a href="#" onclick="confirmTerm(event, '{{ route('admin.payment.paypal') }}')">
								<img alt="Checkout with PayPal" style="width:190px;height:120px;" src="{{ asset('public/template/images/paypal.png') }}" />
							</a>
						</div>
						<button type="button" class="btn btn-success" onclick="confirmTerm(event, '{{ route('admin.payment.cache') }}')"
							style="width:200px;height:50px;margin-left:20px;margin-top:10px;">
							Offline Checkout
						</button>
					</div>
					<!--./ paypal and offline -->
				</div>
			
			</div>
			<!--./ box body -->
		</div>
  </div>
</div>
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