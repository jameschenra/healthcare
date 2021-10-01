@php
  $email = old("email", $user->email);
@endphp

@extends("admin.layouts.layout")
@section("title","Admin Users") 
@section("subtitle","Edit") 
@section("css")
  
@endsection
 
@section("breadcumps")
  <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
  <li><a href="{{route('admin.adminusers.index')}}">Admin Users</a></li>
  <li class="active">Edit</li>
@endsection
 
@section("content")
<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <!-- Default box -->
    <a href="{{route('admin.adminusers.index')}}">
      <i class="fa fa-angle-double-left"></i> Back to all  <span class="text-lowercase">users</span>
    </a><br /><br />

    <form method="POST" action="{{route('admin.adminusers.update', ['id' => $user->id])}}" accept-charset="UTF-8">
      @csrf
      <input name="_method" type="hidden" value="PUT">
      <input name="id" type="hidden" value="{{$user->id}}">

      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Edit user</h3>
        </div>
        <!-- input field row -->
        <div class="box-body">

          <!-- email -->
          <div class="form-group">
            <label>Email</label>
            <span class="required">*</span>
            <input type="email" name="email" value="{{ $email }}" placeholder="Email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" />
            @if ($errors->has("email"))
              <span class="invalid-feedback">{{ $errors->first("email") }}</span> 
            @endif
          </div>
          <!--./ email -->

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
    // http://jsfiddle.net/o31w8k6v/
    $("input[name=birthday]").datepicker({
        autoclose: true,
        format: "yyyy-mm-dd"
    })
  });

</script>
@endsection