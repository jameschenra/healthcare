@php
    
@endphp
@extends("user.layouts.page_layout")

@section('title', 'Upgrade Summary')

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
                <a href="{{ route('user.members.upgrade.showpayment') }}"><button type="button" class="btn btn-secondary mr-5">Checkout</button></a>
                <a href="{{ route('user.members.index') }}"><button type="button" class="btn btn-secondary">Return to list</button></a>
            </div>
        </div>
    </div>
@endsection

@section("js")
<script>
</script>
@endsection