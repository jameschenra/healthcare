@extends("user.layouts.page_layout")

@section('title', 'Add Member')

@section("css")
    <link rel="stylesheet" href="{{ asset('public/template/vendors/datepicker-master/dist/datepicker.min.css') }}" />
@endsection

@section("page_content")
    <div class="row d-flex align-items-stretch no-gutters">
        @include('user.layouts.profile_sidebar')

        <div class="col-md-10 p-4 p-md-5 p-md-0 order-md-last bg-light">
            <form method="POST" action="{{ route('user.membership.store') }}" id="form">
                @csrf

                <input type="hidden" name="submit_type" value="add" />

                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="first_name" class="label">First Name</label>
                        <input id="first_name" name="first_name" type="text" class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" 
                            value="{{ old('first_name') }}" required />
                
                        @if ($errors->has('first_name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('first_name') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="last_name" class="label">Last Name</label>
                        <input id="last_name" name="last_name" type="text" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" 
                            value="{{ old('last_name') }}" required />
                
                        @if ($errors->has('last_name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('last_name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="address" class="label">Address</label>
                        <input id="address" name="address" type="text" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}"
                            value="{{ old('address') }}" required>
                        
                        @if ($errors->has('address'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('address') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="city" class="label">City</label>
                        <input id="city" name="city" type="text" class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}"
                            value="{{ old('city') }}" required>
                        
                        @if ($errors->has('city'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('city') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
        
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="country" class="label">Country</label>
                        <select id="country_id" name="country_id" class="form-control{{ $errors->has('country_id') ? ' is-invalid' : '' }}" required>
                            <option></option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}"{{ old('country_id') == $country->id ? ' selected' : ''}}>{{ $country->country_name }}</option>
                            @endforeach
                        </select>
                        
                        @if ($errors->has('country_id'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('country_id') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="region" class="label">Region</label>
                        <input id="region" name="region" type="text" class="form-control{{ $errors->has('region') ? ' is-invalid' : '' }}"
                            value="{{ old('region') }}" required>
                        
                        @if ($errors->has('region'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('region') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="birthday" class="label">Birthday</label>
                        <input id="birthday" name="birthday" class="datepicker form-control{{ $errors->has('birthday') ? ' is-invalid' : '' }}"
                            value="{{ old('birthday') }}" required readonly>
                        
                        @if ($errors->has('birthday'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('birthday') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="group">
                            <label for="gender" class="label">Gender</label>
                            <select id="gender" name="gender" class="form-control{{ $errors->has('gender') ? ' is-invalid' : '' }}" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                            
                            @if ($errors->has('gender'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('gender') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="email" class="label">Email Address</label>
                        <input id="email" name="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                            value="{{ old('email') }}" required>
                        
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="label">Phone</label>
                        <input id="phone" name="phone" type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                            value="{{ old('phone') }}" required>
                        
                        @if ($errors->has('phone'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('phone') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <!-- password field start -->
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="password" class="label">Password</label>
                        <input id="password" name="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                            autocomplete="new-password" />
                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="password-confirmation" class="label">Re-Enter Password</label>
                        <input id="password-confirmation" name="password_confirmation" type="password" class="form-control" />
                    </div>
                </div>
                <!--./ password field end -->
            
                <br />
                <div class="form-group row">
                    <div class="col-md-3">Relationship to you</div>
                    <div class="col-md-9">
                        @foreach(Utils::RELATIONSHIPS as $index => $relationship)
                            <div class="form-check form-check-inline relations mr-3">
                                <input class="form-check-input" type="radio" name="relationship" id="relationship{{$index}}"
                                    value="{{$relationship}}" required />
                                <label class="form-check-label" for="relationship{{$index}}">{{ $relationship }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <br />
                <div class="row">
                    <div class="col-md-3">Select a plan</div>
                    <div class="col-md-9">
                        @foreach($memberPlanTypes as $plan)
                            <div class="form-check form-check-inline relations mr-5">
                                <input class="form-check-input" type="radio" name="plan_type" id="plantype{{$plan->id}}"
                                    value="{{$plan->id}}" required />
                                <label class="form-check-label" for="plantype{{$plan->id}}">
                                    {{ $plan->name }}
                                    <div class="plan-price-desc">${{$plan->adult_price}}/mo/adult</div>
                                    <div class="plan-price-desc">${{$plan->child_price}}/mo/child</div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <br />
                <div class="form-group text-center">
                    <input id="btn-add" type="submit" value="Add another" style="width:150px;" class="btn btn-secondary py-3 px-4">&nbsp;&nbsp;
                    <input id="btn-checkout" type="submit" style="width:150px;" value="Checkout" class="btn btn-primary py-3 px-5">
                    &nbsp;&nbsp;
                    <a href="{{ route('user.membership.plans') }}">
                        <input type="button" style="width:150px;" value="Cancel" class="btn btn-danger py-3 px-5">
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section("js")
<script src="{{ asset('public/template/vendors/datepicker-master/dist/datepicker.min.js') }}"></script>
<script>
    var planTypes = {!! json_encode($memberPlanTypes) !!};
    var adultOptions = [0, 1, 4]

    $(function(){
        var submitType = 'add';

        $('#birthday').datepicker({
            format: 'yyyy-mm-dd',
            autoHide: true
        });

        $('#birthday').change(function() {
            var date = new Date($("#birthday").val());
            var curYear = (new Date()).getFullYear();
            var birthYear = date.getFullYear();
            var isAdult = (birthYear <= (curYear - 18));

            changeAdultControl(!isAdult);

            for (var index of adultOptions) {
                $('#relationship' + index).prop('disabled', !isAdult);
                if (!isAdult) {
                    $('#relationship' + index).prop('checked', false);
                }
            }
        });

        $('#form').on('submit', function(e){
            $('input[name=submit_type]').val(submitType);
        });

        $('#btn-add').on('click', function(){
            submitType = 'add';
        });
        
        $('#btn-checkout').on('click', function(){
            submitType = 'checkout';
        });
    });

    function changeAdultControl(status) {
        $('#email').prop('disabled', status);
        $('#phone').prop('disabled', status);
        $('#password').prop('disabled', status);
        $('#password-confirmation').prop('disabled', status);
    }
</script>
@endsection