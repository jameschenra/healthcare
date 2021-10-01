@extends("user.layouts.main")

@section("css")
    <style>
        .terms-container {
            min-height: 500px;
            padding-top: 30px;
        }

        .right-content h3:first-child {
            margin-top: -15px;
        }

        .right-content button {
            width: 150px;
        }

        @media (max-width: 1000px) {
            .btn-container {
                width: 100% !important;
                max-width: 100%;
                flex: 0 0 100%;
            }
        }
        
    </style>
@endsection

@section("content")
  <div class="container terms-container">
    <h2 class="mb-5">{{ $plan->name }}</h2>
    <div class="row">
        <div class="col-md-6">
            <p style="font-size: 17px; line-height: 1.7rem">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla ut sem elementum arcu elementum bibendum. Sed ac dui dapibus, aliquam est et, tristique nibh. Aenean dapibus nisl eu leo ultrices scelerisque nec a ligula. Vestibulum viverra sem non purus sollicitudin, quis semper dui bibendum. Curabitur iaculis nulla sit amet feugiat tincidunt. In vel erat ac metus rhoncus lacinia. Quisque sodales nunc ac erat iaculis, a gravida felis auctor. Nam feugiat lacus a volutpat molestie. Vivamus egestas nisi a diam pretium maximus.</p>
        </div>
        <div class="col-md-6 right-content">
            <h3>Plan Maximum:</h3>
            <p>${{ $plan->adult_price * 12 }}</p>

            <h3>Excluded:</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla ut sem elementum arcu elementum bibendum. Sed ac dui dapibus, aliquam est et,</p>

            <div class="row">
                <div class="btn-container col-md-4 mb-4 text-center">
                    <a href="{{ route('user.auth.showSignup') }}"><button type="button" class="btn btn-primary">Sign up</button></a>
                </div>
                <div class="btn-container col-md-4 mb-4 text-center">
                    <button type="button" class="btn btn-secondary">Share</button>
                </div>
                <div class="btn-container col-md-4 mb-4 text-center">
                    <a href="{{ route('home') }}"><button type="button" class="btn btn-danger">Go Back</button></a>
                </div>
            </div>

        </div>
    </div>
  </div>
@endsection

@section("js")

@endsection