@php
    $user = Auth::user();
    $userInfo = $user->userInfo;

    $planName = 'Not membered';
    if ($user->membership) {
        if ($user->membership->planType) {
            $planName = $user->membership->planType->name;
        }
    }
@endphp
@extends("user.layouts.page_layout")

@section('title', 'Profile Edit')

@section("css")
@endsection

@section("page_content")
    <div class="row d-flex align-items-stretch no-gutters">
        @include('user.layouts.profile_sidebar')

        <div class="col-md-10 p-4 p-md-5 order-md-last bg-light">
            <form action="#">
                <div class="form-group row">
                    <div class="col-md-2">
                        user name:
                    </div>
                    <div class="col-md-8">
                        {{ $userInfo->first_name }} {{ $userInfo->last_name }} 
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2">
                        gender:
                    </div>
                    <div class="col-md-8">
                        {{ $userInfo->gender }}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2">
                        membership:
                    </div>
                    <div class="col-md-8">
                        {{ $planName }}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2">
                        email:
                    </div>
                    <div class="col-md-8">
                        {{ $user->email }}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2">
                        phone:
                    </div>
                    <div class="col-md-8">
                        {{ $userInfo->phone }} 
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2">
                        birthday:
                    </div>
                    <div class="col-md-8">
                        {{ $userInfo->birthday }} 
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2">
                        Adddress:
                    </div>
                    <div class="col-md-8">
                        {{ $userInfo->address }} 
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2">
                        City:
                    </div>
                    <div class="col-md-8">
                        {{ $userInfo->city }} 
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2">
                        Country:
                    </div>
                    <div class="col-md-8">
                        {{ $userInfo->country->country_name }}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2">
                        Region:
                    </div>
                    <div class="col-md-8">
                        {{ $userInfo->region }} 
                    </div>
                </div>
                <br />
                @if(Utils::isPrimary())
                    <div class="form-group text-center">
                        <a href="{{ route('user.profile.edit') }}" class="btn btn-secondary py-3 px-5">Edit</a>
                    </div>
                @endif
            </form>
        </div>
    </div>
@endsection

@section("js")

@endsection