@php
    $first_name = old('first_name', $member->first_name);
    $last_name = old('last_name', $member->last_name);
    $address = old('address', $member->address);
    $city = old('city', $member->city);
    $country_id = old('country_id', $member->country_id);
    $region = old('region', $member->region);
    $birthday = old('birthday', $member->birthday);
    $gender = old('gender', $member->gender);
    $email = old('email', $member->email);
    $phone = old('phone', $member->phone);
    $relationship = old('relationship', $member->relationship);
@endphp
@extends("user.layouts.page_layout")

@section('title', 'Edit Member')

@section("css")
    <link rel="stylesheet" href="{{ asset('public/template/vendors/datepicker-master/dist/datepicker.min.css') }}" />
@endsection

@section("page_content")
    <div class="row d-flex align-items-stretch no-gutters">
        @include('user.layouts.profile_sidebar')

        <div class="col-md-10 p-4 p-md-5 p-md-0 order-md-last bg-light">
            <form method="POST" action="{{ route('user.membership.update') }}">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="id" value="{{$member->id}}">

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
                        <select id="country_id" name="country_id" class="form-control{{ $errors->has('country_id') ? ' is-invalid' : '' }}" required>
                            <option></option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}"{{ $country_id == $country->id ? ' selected' : ''}}>{{ $country->country_name }}</option>
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
                        <div class="group">
                            <label for="gender" class="label">Gender</label>
                            <select id="gender" name="gender" class="form-control{{ $errors->has('gender') ? ' is-invalid' : '' }}" required>
                                @foreach(Utils::GENDERS as $genderItem)
                                    <option value="{{ $genderItem }}"{{ $genderItem == $gender? ' selected' : '' }}>{{ $genderItem }}</option>
                                @endforeach
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
                            value="{{ $email }}" required>
                        
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

                <br />
                <div class="form-group row">
                    <div class="col-md-3">Relationship to you</div>
                    <div class="col-md-9">
                        @foreach(Utils::RELATIONSHIPS as $index => $relationshipItem)
                            <div class="form-check form-check-inline relations mr-3">
                                <input class="form-check-input" type="radio" name="relationship" id="relationship{{$index}}"
                                    value="{{$relationshipItem}}"{{$relationship == $relationshipItem ? ' checked' : ''}} required />
                                <label class="form-check-label" for="relationship{{$index}}">{{ $relationshipItem }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <br />
                <div class="form-group text-center">
                    <input type="submit" value="Update" style="width:150px;" class="btn btn-secondary py-3 px-4">&nbsp;&nbsp;
                    <a href="{{ route('user.pendings.index') }}">
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
    var adultOptions = [0, 1, 4]

    $(function(){
        $('#birthday').datepicker({
            format: 'yyyy-mm-dd',
            autoHide: true
        });

        disableAdultControl();
        
        $('#birthday').change(function() {
            disableAdultControl();
        });

        function disableAdultControl() {
            var date = new Date($("#birthday").val());
            var curYear = (new Date()).getFullYear();
            var birthYear = date.getFullYear();
            var isAdult = (birthYear <= (curYear - 18));

            if (isAdult) {
                $('#email').prop('disabled', false);
                $('#phone').prop('disabled', false);
            } else {
                $('#email').prop('disabled', true);
                $('#phone').prop('disabled', true);
            }

            for (var index of adultOptions) {
                $('#relationship' + index).prop('disabled', !isAdult);
                if (!isAdult) {
                    $('#relationship' + index).prop('checked', false);
                }
            }
        }
    });
</script>
@endsection