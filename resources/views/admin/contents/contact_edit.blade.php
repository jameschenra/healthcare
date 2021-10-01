@extends("admin.layouts.layout")
@php
  $email = old("email", $content->email);
  $phone = old("phone", $content->phone);
  $address = old("address", $content->address);
  $url = old("address", $content->url);
  $facebook = old("facebook", $content->facebook);
  $twitter = old("twitter", $content->twitter);
  $instagram = old("instagram", $content->instagram);
  $pinterest = old("pinterest", $content->pinterest);
@endphp
@section("title","Contact Informations") 
@section("subtitle","Edit") 
 
@section("breadcumps")
  <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
  <li><a href="#">Contact Informations</a></li>
  <li class="active">Edit</li>
@endsection

@section("content")
<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <!-- Default box -->
    <form method="POST" action="{{route('admin.contents.contact.update')}}">

      @csrf

      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Edit</h3>
        </div>
        <div class="box-body form-horizontal">

          <!-- email -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
              <input type="email" name="email" value="{{ $email }}" placeholder="Email"
                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" />
              @if ($errors->has("email"))
                <span class="invalid-feedback">{{ $errors->first("email") }}</span>
              @endif
            </div>
          </div>
          <!--./ email -->

          <!-- phone -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Phone</label>
            <div class="col-sm-10">
              <input type="text" name="phone" value="{{ $phone }}" placeholder="Phone"
                class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" />
              @if ($errors->has("phone"))
                <span class="invalid-feedback">{{ $errors->first("phone") }}</span>
              @endif
            </div>
          </div>
          <!--./ phone -->

          <!-- address -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Address</label>
            <div class="col-sm-10">
              <input type="text" name="address" value="{{ $address }}" placeholder="Address"
                class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" />
              @if ($errors->has("address"))
                <span class="invalid-feedback">{{ $errors->first("address") }}</span>
              @endif
            </div>
          </div>
          <!--./ address -->

          <!-- url -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Url</label>
            <div class="col-sm-10">
              <input type="text" name="url" value="{{ $url }}" placeholder="Url"
                class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" />
              @if ($errors->has("url"))
                <span class="invalid-feedback">{{ $errors->first("url") }}</span>
              @endif
            </div>
          </div>
          <!--./ url -->

          <!-- facebook -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Facebook</label>
            <div class="col-sm-10">
              <input type="text" name="facebook" value="{{ $facebook }}" placeholder="Facebook"
                class="form-control {{ $errors->has('facebook') ? 'is-invalid' : '' }}" />
              @if ($errors->has("facebook"))
                <span class="invalid-feedback">{{ $errors->first("facebook") }}</span>
              @endif
            </div>
          </div>
          <!--./ facebook -->

          <!-- twitter -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Twitter</label>
            <div class="col-sm-10">
              <input type="text" name="twitter" value="{{ $twitter }}" placeholder="Twitter"
                class="form-control {{ $errors->has('twitter') ? 'is-invalid' : '' }}" />
              @if ($errors->has("twitter"))
                <span class="invalid-feedback">{{ $errors->first("twitter") }}</span>
              @endif
            </div>
          </div>
          <!--./ twitter -->

          <!-- instagram -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Instagram</label>
            <div class="col-sm-10">
              <input type="text" name="instagram" value="{{ $instagram }}" placeholder="Instagram"
                class="form-control {{ $errors->has('instagram') ? 'is-invalid' : '' }}" />
              @if ($errors->has("instagram"))
                <span class="invalid-feedback">{{ $errors->first("instagram") }}</span>
              @endif
            </div>
          </div>
          <!--./ instagram -->

          <!-- pinterest -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Pinterest</label>
            <div class="col-sm-10">
              <input type="text" name="pinterest" value="{{ $pinterest }}" placeholder="Pinterest"
                class="form-control {{ $errors->has('pinterest') ? 'is-invalid' : '' }}" />
              @if ($errors->has("pinterest"))
                <span class="invalid-feedback">{{ $errors->first("pinterest") }}</span>
              @endif
            </div>
          </div>
          <!--./ pinterest -->

        </div>

        <div class="box-footer">
          <div id="saveActions" class="form-group text-center">
            <div class="btn-group">
              <button type="submit" class="btn btn-success">
                <span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;
                <span data-value="save_and_back">Save</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
 
@section("js")

@endsection