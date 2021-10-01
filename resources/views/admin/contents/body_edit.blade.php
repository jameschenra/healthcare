@extends("admin.layouts.layout")
@php
  $contentText = old("content", $content->content);
  $image = old("image", $content->image);
  $src = $content->image ? asset('public/files/contents/' . $content->image) : 'data:image/gif;base64,R0lGODlhAQABAAAAACwAAAAAAQABAAA=';
@endphp
@section("title", $content->title) 
@section("subtitle","Edit") 
 
@section("breadcumps")
  <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
  <li><a href="#">Contents</a></li>
  <li class="active">Edit</li>
@endsection

@section("content")
<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <form method="POST" action="{{route('admin.contents.body.update')}}" enctype="multipart/form-data" accept-charset="UTF-8">

      @csrf
      <input name="id" type="hidden" value="{{$content->id}}" />

      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Edit</h3>
        </div>
        <div class="box-body form-horizontal">
          <!-- content -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Content *</label>
            <div class="col-sm-10">
              <textarea name="content" maxlength="1000" rows="5" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}">{{ $contentText }}</textarea>
              @if ($errors->has("content"))
                <span class="invalid-feedback">{{ $errors->first("content") }}</span> 
              @endif
            </div>
          </div>
          <!--./ content -->
          
          <!-- Image -->
          <div class="form-group" style="margin-top: 10px;">
            <label class="col-sm-2 control-label">Image *</label>
            <div class="col-sm-4">
              <input type="file" id="img_file" name="img_file" value="{{old('img_file')}}" accept="image/*"
                class="form-control {{ $errors->has('img_file') ? 'is-invalid' : '' }}"/>
              @if ($errors->has("img_file"))
                <span class="invalid-feedback">{{ $errors->first("img_file") }}</span>
              @endif
            </div>
            <div class="col-sm-6">
              <img id="thumb-icon" src="{{ $src }}" accept=".png" alt="thumbnail"/>
            </div>
          </div>
          <!--./ Image -->
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
<script>
  var fileTypes = ['jpg', 'jpeg', 'png'];

  function readURL(input) {
      if (input.files && input.files[0]) {
        var extension = input.files[0].name.split('.').pop().toLowerCase(),  //file extension from input file
            imgType = fileTypes.indexOf(extension) > -1;
        
        if (imgType) {
          var reader = new FileReader();

          reader.onload = function (e) {
              $('#thumb-icon').attr('src', e.target.result);
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