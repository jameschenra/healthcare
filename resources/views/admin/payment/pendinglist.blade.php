@php

@endphp

@extends("admin.layouts.layout")
@section("title","Pending members") 
@section("subtitle","Checkout") 

@section("css")
@endsection
 
@section("breadcumps")
  <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
  <li><a href="{{route('admin.users')}}">Users</a></li>
  <li class="active">Payment/PendingMembers</li>
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
				<h3 class="box-title">Pending Members</h3>
			</div>
			
			<!-- box body -->
			<div class="box-body" style="padding: 15px;">
				@if (session('payment_error'))
					<div class="alert alert-danger">
						{{ session('payment_error') }}
					</div>
				@endif
          
                @if(count($pendingMembers) > 0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Plan</th>
                                <th>Relationship</th>
                                <th>Monthly</th>
                                <th>Yearly</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach($pendingMembers as $member)
                                <tr>
                                    <td>{{ $member->user->userInfo->first_name . ' ' . $member->user->userInfo->last_name}}</td>
                                    <td>{{ $member->planType->name }}</td>
                                    <td>{{ $member->user->relationship }}</td>
                                    <td>
                                        @if($member->user->is_adult)
                                            $ {{ $member->planType->adult_price}}
                                        @else
                                            $ {{ $member->planType->child_price}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($member->user->is_adult)
                                            $ {{ $member->planType->adult_price * 12 }}
                                        @else
                                            $ {{ $member->planType->child_price * 12 }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-center">
                        There is no any pending members.
                    </p>
                @endif
                <br />
                
                <div class="text-center">
                    @if(count($pendingMembers) > 0)
                        <form method="post" action="{{ route('admin.payment.pendingcheckout') }}" style="display: inline">
                            @csrf

                            <input type="hidden" name="owner_id" value="{{ $id }}" />

                            <button type="submit" class="btn btn-primary" style="width: 100px">Checkout</button>&nbsp;
                        </form>
                    @endif
                    <a href="{{ route('admin.users') }}">
                        <button type="button" class="btn btn-danger" style="width: 100px">Back</button>
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