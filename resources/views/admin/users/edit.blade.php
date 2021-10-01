@php
  $email = old("email", $user->email);
  $first_name = old("first_name", $user->userInfo->first_name);
  $last_name = old("last_name", $user->userInfo->last_name);
  $address = old("address", $user->userInfo->address);
  $city = old("city", $user->userInfo->city);
  $country_id = old("country_id", $user->userInfo->country_id);
  $region = old("region", $user->userInfo->region);
  $phone = old("phone", $user->userInfo->phone);
  $gender = old("gender", $user->userInfo->gender);
  $birthday = old("birthday", $user->userInfo->birthday);
  $relationship = old("relationship", $user->relationship);

  $plan_type = null;
  $isPending = 0;
  if ($user->membership) {
    $plan_type = old('plan_type', $user->membership->plan_type_id);
  } else {
    $isPending = 1;
    $plan_type = old('plan_type', $user->membership_pending->plan_type_id);
  }

  $primary_id = $user->primary_id;
@endphp

@extends("admin.layouts.layout")
@section("title","Users") 
@section("subtitle","Edit") 
<style>
  select[readonly]{
    background: #eee;
    cursor:no-drop;
  }

  select[readonly] option{
    display:none;
  }
</style>
@section("css")
  
@endsection
 
@section("breadcumps")
  <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
  <li><a href="{{route('admin.users')}}">Users</a></li>
  <li class="active">Edit</li>
@endsection
 
@section("content")
<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <!-- Default box -->
    <a href="{{route('admin.users')}}">
      <i class="fa fa-angle-double-left"></i> Back to all  <span class="text-lowercase">users</span>
    </a><br /><br />

    @if ($errors->any())
      <div class="alert alert-danger">
          {{ $errors->first() }}
      </div>
    @endif
    <form method="POST" action="{{route('admin.users.update', ['id' => $user->id])}}" accept-charset="UTF-8">
      @csrf
      <input name="_method" type="hidden" value="PUT">
      <input name="id" type="hidden" value="{{$user->id}}">

      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Edit user</h3>
        </div>
        <!-- input field row -->
        <div class="box-body">

          <div class="row">
            <!-- Relationship -->
            <div class="form-group col-md-6">
              <label>Relationship</label>
              <span class="required">*</span>
              <select name="relationship" class="form-control" class="form-control {{ $errors->has('relationship') ? 'is-invalid' : '' }}">
                @if($relationship == 'Primary')
                  <option value="Primary" {{ $relationship == 'Primary' ? 'selected' : '' }}>Primary</option>
                @else
                  @foreach (Utils::RELATIONSHIPS as $relation)
                    <option value="{{ $relation }}" {{ $relationship == $relation ? 'selected' : '' }}>{{ $relation }}</option>
                  @endforeach
                @endif
              </select>
              @if ($errors->has("relationship"))
                <span class="invalid-feedback">{{ $errors->first("relationship") }}</span>
              @endif
            </div>
            <!--./ Relationship -->

            <!-- primary member -->
            <div class="form-group col-md-6">
              <label>Primary Member</label>
              <span class="required">*</span>
              <select name="primary_id" class="form-control" class="form-control {{ $errors->has('primary_id') ? 'is-invalid' : '' }}" readonly>
                <option value=""></option>
                @foreach($primaryMembers as $pm)
                  <option value="{{ $pm->id }}" {{ $primary_id == $pm->id ? 'selected' : '' }}>{{ $pm->email }}</option>
                @endforeach
              </select>
              @if ($errors->has("primary_id"))
                <span class="invalid-feedback">{{ $errors->first("primary_id") }}</span>
              @endif
            </div>
            <!--./ primary member -->
          </div>

          <div class="row">
            <!-- first name -->
            <div class="form-group col-md-6">
              <label>First Name</label>
              <span class="required">*</span>
              <input type="text" name="first_name" value="{{ $first_name }}" placeholder="First Name" class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}" />
              @if ($errors->has("first_name"))
                <span class="invalid-feedback">{{ $errors->first("first_name") }}</span>
              @endif
            </div>
            <!--./ first name -->

            <!-- last name -->
            <div class="form-group col-md-6">
              <label>Last Name</label>
              <span class="required">*</span>
              <input type="text" name="last_name" value="{{ $last_name }}" placeholder="Last Name" class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}" />
              @if ($errors->has("last_name"))
                <span class="invalid-feedback">{{ $errors->first("last_name") }}</span>
              @endif
            </div>
            <!--./ last name -->
          </div>

          <div class="row">
            <!-- address -->
            <div class="form-group col-md-6">
              <label>Address</label>
              <span class="required">*</span>
              <input type="text" name="address" value="{{ $address }}" placeholder="Address" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" />
              @if ($errors->has("address"))
                <span class="invalid-feedback">{{ $errors->first("address") }}</span>
              @endif
            </div>
            <!--./ address -->

            <!-- city -->
            <div class="form-group col-md-6">
              <label>City</label>
              <span class="required">*</span>
              <input type="text" name="city" value="{{ $city }}" placeholder="City" class="form-control {{ $errors->has('city') ? 'is-invalid' : '' }}" />
              @if ($errors->has("city"))
                <span class="invalid-feedback">{{ $errors->first("city") }}</span>
              @endif
            </div>
            <!--./ city -->
          </div>

          <div class="row">
            <!-- country  -->
            <div class="form-group col-md-6">
              <label>Country</label>
              <span class="required">*</span>
              <select id="country_id" name="country_id" class="form-control{{ $errors->has('country_id') ? ' is-invalid' : '' }}" required>
                <option></option>
                @foreach($countries as $country)    
                    <option value="{{ $country->id }}"{{ $country_id == $country->id ? ' selected' : ''}}>
                        {{ $country->country_name }}
                    </option>
                @endforeach
              </select>
              @if ($errors->has("country_id"))
                <span class="invalid-feedback">{{ $errors->first("country_id") }}</span>
              @endif
            </div>
            <!--./ country -->

            <!-- region -->
            <div class="form-group col-md-6">
              <label>Region</label>
              <span class="required">*</span>
              <input type="text" name="region" value="{{ $region }}" placeholder="Region" class="form-control {{ $errors->has('region') ? 'is-invalid' : '' }}" />
              @if ($errors->has("region"))
                <span class="invalid-feedback">{{ $errors->first("region") }}</span>
              @endif
            </div>
            <!--./ region -->
          </div>

          <div class="row">
            <!-- email -->
            <div class="form-group col-md-6">
              <label>Email</label>
              <span class="required">*</span>
              <input type="email" name="email" value="{{ $email }}" placeholder="Email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" readonly/>
              @if ($errors->has("email"))
                <span class="invalid-feedback">{{ $errors->first("email") }}</span> 
              @endif
            </div>
            <!--./ email -->

            <!-- phone -->
            <div class="form-group col-md-6">
              <label>Phone</label>
              <span class="required">*</span>
              <input type="text" name="phone" value="{{ $phone }}" placeholder="Phone" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" />
              @if ($errors->has("phone"))
                <span class="invalid-feedback">{{ $errors->first("phone") }}</span>
              @endif
            </div>
            <!--./ phone -->
          </div>

          <div class="row">
            <!-- gender -->
            <div class="form-group col-md-6">
              <label>Gender</label>
              <span class="required">*</span>
              <select id="gender" name="gender" class="form-control{{ $errors->has('gender') ? ' is-invalid' : '' }}" required>
                @foreach(Utils::GENDERS as $gender_item)
                  <option value="{{$gender_item}}"{{$gender == $gender_item? ' selected' : ''}}>{{ $gender_item }}</option>
                @endforeach
              </select>
              @if ($errors->has("gender"))
                <span class="invalid-feedback">{{ $errors->first("gender") }}</span>
              @endif
            </div>
            <!--./ gender -->

            <!-- birthday -->
            <div class="form-group col-md-6">
              <label>Birthday</label>
              <span class="required">*</span>
              <input type="text" name="birthday" value="{{ $birthday }}" placeholder="Birthday" class="form-control {{ $errors->has('birthday') ? 'is-invalid' : '' }}" />
              @if ($errors->has("birthday"))
                <span class="invalid-feedback">{{ $errors->first("birthday") }}</span>
              @endif
            </div>
            <!--./ birthday -->
          </div>

          <div class="row">
            <!-- password -->
            <div class="form-group col-md-6">
              <label>Password</label>
              <span class="required">*</span>
              <input type="password" name="password" placeholder="Password"
                class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" />
              @if ($errors->has("password"))
                <span class="invalid-feedback">{{ $errors->first("password") }}</span>
              @endif
            </div>
            <!--./ password -->
            <!-- password confirmation -->
            <div class="form-group col-md-6">
              <label>Password Confirm</label>
              <span class="required">*</span>
              <input type="password" name="password_confirmation" placeholder="Password Confirm"
                class="form-control" />
            </div>
            <!--./ password confirmation -->
          </div>

          <div class="row">
            <!-- Membership plan type -->
            <div class="form-group col-md-6">
              <label>Membership Plan</label>
              <span class="required">*</span>
              <select name="plan_type" class="form-control{{ $errors->has('plan_type') ? ' is-invalid' : '' }}">
                <option value="0" {{ $plan_type == 0 ? 'selected' : '' }}>No Plan</option>
                @foreach($memberPlanTypes as $plan)
                  @if($isPending || ($plan->id >= $plan_type))
                    <option value="{{ $plan->id }}" {{ $plan_type == $plan->id ? 'selected' : ''}}>{{ $plan->name }}</option>
                  @endif
                @endforeach
              </select>
              
            </div>
            <!--./ Membership plan type -->
          </div>
        </div>
        <!--./ input field row -->

        <div class="box-footer">
          <div class="form-group text-center saveActions">
            <input type="hidden" name="save_action" value="save_and_back" />
            <div class="btn-group">
              <button type="submit" class="btn btn-success">
                <span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;
                <span data-value="save_and_back">Save and back</span>
              </button>
            </div>
            <a href="{{route('admin.users')}}" class="btn btn-danger"><span class="fa fa-ban"></span> &nbsp;Cancel</a>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
 
@section("js")
<script>
  $(function(){
    var userType = "{{ $user['relationship'] }}";
    var planType = "{{ $plan_type }}";
    var isPending = "{{ $isPending }}";

    if (userType != 'Primary' || (isPending == 0 && planType != 0)) {
      $('select[name=plan_type] option[value=0]').remove();
    }

    if (userType == 'Son' || userType == 'Daughter') {
      $('input[name=email]').prop('disabled', true);
      $('input[name=phone]').prop('disabled', true);

      $('input[name=password]').prop('disabled', true);
      $('input[name=password_confirmation]').prop('disabled', true);
    }

    $('select[name=relationship]').on('change', function(e){
      var disableControl = true;
      if (this.value != 'Son' && this.value != 'Daughter') {
        disableControl = false;
      }

      $('input[name=email]').prop('disabled', disableControl);
      $('input[name=phone]').prop('disabled', disableControl);

      $('input[name=password]').prop('disabled', disableControl);
      $('input[name=password_confirmation]').prop('disabled', disableControl);
    });

    $("input[name=birthday]").datepicker({
        autoclose: true,
        format: "yyyy-mm-dd"
    });

  });

</script>
@endsection