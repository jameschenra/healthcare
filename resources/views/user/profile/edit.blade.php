@php
    $user = Auth::user();
    $userInfo = $user->userInfo;
    $first_name = old('first_name', $userInfo->first_name);
    $last_name = old('last_name', $userInfo->last_name);
    $email = old('email', $user->email);
    $phone = old('phone', $userInfo->phone);
    $birthday = old('birthday', $userInfo->birthday);
    $gender = old('gender', $userInfo->gender);
    $address = old('address', $userInfo->address);
    $city = old('city', $userInfo->city);
    $country_id = old('country', $userInfo->country->id);
    $region = old('region', $userInfo->region);
@endphp
@extends("user.layouts.page_layout")

@section('title', 'Profile Edit')

@section("css")
    <link rel="stylesheet" href="{{ asset('public/template/vendors/datepicker-master/dist/datepicker.min.css') }}" />
@endsection

@section("page_content")
    <div class="row d-flex align-items-stretch no-gutters">
        @include('user.layouts.profile_sidebar')

        <div class="col-md-10 p-4 p-md-5 p-md-0 order-md-last bg-light">
            <form method="POST" action="{{ route('user.profile.update') }}">
                @csrf

                <input name="_method" type="hidden" value="PUT">
                <input name="id" type="hidden" value="{{ $user->id }}">
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="first_name" class="label">First Name</label>
                        <input id="first_name" name="first_name" type="text" class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" 
                            value="{{ $first_name }}" required />
                
                        @if ($errors->has('first_name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('first_name') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="last_name" class="label">Last Name</label>
                        <input id="last_name" name="last_name" type="text" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" 
                            value="{{ $last_name }}" required />
                
                        @if ($errors->has('last_name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('last_name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="email" class="label">Email Address</label>
                        <input id="email" name="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                            value="{{ $email }}" readonly>
                        
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="label">Phone</label>
                        <input id="phone" name="phone" type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                            value="{{ $phone }}" required>
                        
                        @if ($errors->has('phone'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('phone') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="birthday" class="label">Birthday</label>
                        <input id="birthday" name="birthday" class="datepicker form-control{{ $errors->has('birthday') ? ' is-invalid' : '' }}"
                            value="{{ $birthday }}" required readonly>
                        
                        @if ($errors->has('birthday'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('birthday') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="gender" class="label">Gender</label>
                        <select id="gender" name="gender" class="form-control{{ $errors->has('gender') ? ' is-invalid' : '' }}" required>
                            @foreach(Utils::GENDERS as $generItem)
                            <option value="{{ $generItem }}" {{ $gender == $generItem ? 'selected' : ''}}>{{ $generItem }}</option>
                            @endforeach
                        </select>
                        
                        @if ($errors->has('gender'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('gender') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="address" class="label">Address</label>
                        <input id="address" name="address" type="text" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}"
                            value="{{ $address }}" required>
                        
                        @if ($errors->has('address'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('address') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="city" class="label">City</label>
                        <input id="city" name="city" type="text" class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}"
                            value="{{ $city }}" required>
                        
                        @if ($errors->has('city'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('city') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
        
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="country_id" class="label">Country</label>
                        <select id="country_id" name="country_id" class="form-control{{ $errors->has('country_id') ? ' is-invalid' : '' }}">
                            <option></option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}"{{ $country->id == $country_id ? ' selected' : ''}}>{{ $country->country_name }}</option>
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
                            value="{{ $region }}" required>
                        
                        @if ($errors->has('region'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('region') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="password" class="label">Password</label>
                        <input id="password" name="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                            autocomplete="new-password">
                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="password-confirmation" class="label">Re-Enter Password</label>
                        <input id="password-confirmation" name="password_confirmation" type="password" class="form-control">
                    </div>
                </div>
    
                <br />
                <div class="form-group text-center">
                    <input type="submit" value="Update" class="btn btn-primary py-3 px-5">&nbsp;&nbsp;
                    <a href="{{ route('user.profile.index') }}"><input type="button" value="Cancel" class="btn btn-danger py-3 px-5"></a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section("js")
<script src="{{ asset('public/template/vendors/datepicker-master/dist/datepicker.min.js') }}"></script>
<script>
    $(function(){
        var date = new Date();
        var minDate = new Date();
        // $('#birthday').attr('max', (date.getFullYear() - 18) + '-12-31');
        $('#birthday').datepicker({
            format: 'yyyy-mm-dd',
            autoHide: true,
            endDate: (date.getFullYear() - 18) + '-12-31'
        });
    });
</script>
@endsection