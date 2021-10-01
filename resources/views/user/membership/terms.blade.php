@php
    
@endphp
@extends("user.layouts.page_layout")

@section('title', 'Profile Edit')

@section("css")
    <style>
        .action-group button{
            height: 70px;
        }
    </style>
@endsection

@section("page_content")
    <div class="row">
        <div class="col-md-6">
            @if($planTypeId == 1)
                <h2>Silver Plan</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Nulla ut sem elementum arcu elementum bibendum.
                    Sed ac dui dapibus, aliquam est et, tristique nibh.
                    Aenean dapibus nisl eu leo ultrices scelerisque nec a ligula.
                    Vestibulum viverra sem non purus sollicitudin, quis semper dui
                    bibendum.
                    Curabitur iaculis nulla sit amet feugiat tincidunt.
                    In vel erat ac metus rhoncus lacinia.
                    Quisque sodales nunc ac erat iaculis, a gravida felis auctor.
                    Nam feugiat lacus a volutpat molestie.
                    Vivamus egestas nisi a diam pretium maximus.</p>
            @elseif($planTypeId == 2)
                <h2>Gold Plan</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Nulla ut sem elementum arcu elementum bibendum.
                    Sed ac dui dapibus, aliquam est et, tristique nibh.
                    Aenean dapibus nisl eu leo ultrices scelerisque nec a ligula.
                    Vestibulum viverra sem non purus sollicitudin, quis semper dui
                    bibendum.
                    Curabitur iaculis nulla sit amet feugiat tincidunt.
                    In vel erat ac metus rhoncus lacinia.
                    Quisque sodales nunc ac erat iaculis, a gravida felis auctor.
                    Nam feugiat lacus a volutpat molestie.
                    Vivamus egestas nisi a diam pretium maximus.</p>
            @elseif($planTypeId == 3)
                <h2>Platinum Plan</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Nulla ut sem elementum arcu elementum bibendum.
                    Sed ac dui dapibus, aliquam est et, tristique nibh.
                    Aenean dapibus nisl eu leo ultrices scelerisque nec a ligula.
                    Vestibulum viverra sem non purus sollicitudin, quis semper dui
                    bibendum.
                    Curabitur iaculis nulla sit amet feugiat tincidunt.
                    In vel erat ac metus rhoncus lacinia.
                    Quisque sodales nunc ac erat iaculis, a gravida felis auctor.
                    Nam feugiat lacus a volutpat molestie.
                    Vivamus egestas nisi a diam pretium maximus.</p>
            @endif
        </div>
        <div class="col-md-6">
            <br />
            <br />
            <h3>Plan Maximum:</h3>
            <p>${{ $plan->adult_price * 12 }}</p>

            <h3>Excluded:</h3>
            <p>Nulla ut sem elementum arcu elementum bibendum.
                Sed ac du.
            </p>

            <div class="row action-group">
                <div class="col">
                    <a href="{{ route('user.membership.signup', [$planTypeId]) }}"><button type="button" class="w-100 btn btn-primary">Signup!</button></a>
                </div>
                <div class="col">
                    <button type="button" class="w-100 btn btn-secondary">Share w/Friend</button>
                </div>
                <div class="col">
                    <a href="{{ route('user.membership.index') }}"><button type="button" class="w-100 btn btn-danger">Go Back</button></a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("js")
@endsection