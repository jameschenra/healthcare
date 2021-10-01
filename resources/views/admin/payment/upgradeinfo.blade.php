@php

@endphp

@extends("admin.layouts.layout")
@section("title","Upgrade Information") 
@section("subtitle","Checkout")

@section("css")
<style>
    h4 {
        margin-bottom: 15px;
    }
</style>
@endsection
 
@section("breadcumps")
  <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
  <li><a href="{{route('admin.users')}}">Users</a></li>
  <li class="active">Payment/Upgrade Info</li>
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
				<h3 class="box-title">Upgrade Information</h3>
			</div>
			
			<!-- box body -->
			<div class="box-body" style="padding: 40px;">
				<h3 class="text-center">
                    Upgrade "{{ $membership->user->relationship }}"'s plan from "{{ $membership->planType->name }}" to "{{ $targetPlan->name}}"
                </h3>
                <br />

                <h4>
                    Membership will be expired by "{{ $membership->expires_in }}"
                </h4>

                <h4>
                    "{{ $membership->planType->name }}" Monthly Price: 
                    @if ($membership->user->is_adult)
                        ${{ $membership->planType->adult_price }}
                    @else
                        ${{ $membership->planType->child_price }}
                    @endif
                </h4>
    
                <h4>
                    "{{ $targetPlan->name }}" Monthly Price: 
                    @if ($membership->user->is_adult)
                        ${{ $targetPlan->adult_price }}
                    @else
                        ${{ $targetPlan->child_price }}
                    @endif
                </h4>
    
                <h4>
                    Rest Month: {{ $restMonth }}
                </h4>
    
                <h4>
                    Amount to pay: ${{ $amount }}
                </h4>

                <div class="text-center">
                    <a href="{{ route('admin.payment.checkout') }}">
                        <button type="button" class="btn btn-primary" style="width: 100px; margin-right: 10px;">
                            Checkout
                        </button>
                    </a>
                    <a href="{{ route('admin.users') }}">
                        <button type="button" class="btn btn-danger" style="width: 100px">Cancel</button>
                    </a>
                </div>
                
			</div>
			<!--./ box body -->
		</div>
  </div>
</div>
@endsection
 
@section("js")
<script>
 
</script>
@endsection