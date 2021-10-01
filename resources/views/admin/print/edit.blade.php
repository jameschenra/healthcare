@php
  $membership_number = $user->membership->membership_number;
  $memberName = $user->userInfo->first_name . ' ' . $user->userInfo->last_name;
  $birthday = $user->userInfo->birthday;
  
  $effectDate = Carbon\Carbon::parse($user->membership->created_at)->format('m/d/Y') 
    . ' - ' . Carbon\Carbon::parse($user->membership->expires_in)->format('m/d/Y');
  
  $photo = 'data:image/gif;base64,R0lGODlhAQABAAAAACwAAAAAAQABAAA=';
  if ($user->userInfo->photo) {
    $photo = asset('public/files/users/' . $user->id . '/' . $user->userInfo->photo);
  }
  
@endphp

@extends("admin.layouts.layout")
@section("title","Print") 
@section("subtitle","Edit") 
@section("css")
  
@endsection
 
@section("breadcumps")
  <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
  <li><a href="{{route('admin.users')}}">Users</a></li>
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

    <form method="POST" action="{{route('admin.users.print.uploadphoto', ['id' => $user->id])}}" enctype="multipart/form-data" accept-charset="UTF-8" id="form-upload">
      @csrf

      <input name="user_id" type="hidden" value="{{$user->id}}">

      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Edit print</h3>
        </div>
        <!-- input field row -->
        <div class="box-body">

          <div class="form-group row">
            <label class="col-md-3 control-label">ID:</label>
            <div class="col-md-9">{{ $membership_number }}</div>
          </div>

          <div class="form-group row">
            <label class="col-md-3 control-label">User Name:</label>
            <div class="col-md-9">{{ $memberName }}</div>
          </div>

          <div class="form-group row">
            <label class="col-md-3 control-label">Birthday:</label>
            <div class="col-md-9">{{ $birthday }}</div>
          </div>

          <div class="form-group row">
            <label class="col-md-3 control-label">Effective Date:</label>
            <div class="col-md-9">{{ $effectDate }}</div>
          </div>

          <div class="form-group row">
            <label class="col-md-3 control-label">Image:</label>
            <div class="col-md-5">
              <div class="img-print-container">
                <img class="img-print-thumbnail" src="{{ $photo }}" accept=".png" alt="thumbnail" />
              </div>
              <label class="custom-file-upload">
                <input id="img_file" name="img_file" type="file" style="display: none"/>
                <i class="fa fa-cloud-upload"></i> Change
              </label>
            </div>
          </div>

        </div>
        <!--./ input field row -->

        <div class="box-footer">
          <div class="form-group text-center saveActions">
            <div class="btn-group">
              <a href="{{ route('admin.users.print.showprint', [$user->id]) }}" class="btn btn-success" target="_blank">
                <span class="fa fa-print" role="presentation" aria-hidden="true"></span> &nbsp;
                <span data-value="save_and_back">Print</span>
              </a>
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
  var fileTypes = ['jpg', 'jpeg', 'png'];

  function readURL(input) {
      if (input.files && input.files[0]) {
        var extension = input.files[0].name.split('.').pop().toLowerCase(),  //file extension from input file
            imgType = fileTypes.indexOf(extension) > -1;
        
        if (imgType) {
          var reader = new FileReader();

          reader.onload = function (e) {
            $('.img-print-thumbnail').attr('src', e.target.result);
            $('#form-upload').submit();
          }

          reader.readAsDataURL(input.files[0]);
        } else {
          alert('You can select only image file.');
          var imgInput = $('#img_file');
          imgInput.replaceWith(imgInput.val('').clone(true));
        }
      }
  }

  $("#img_file").change(function(){
    readURL(this);
  });
</script>
@endsection