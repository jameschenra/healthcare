@extends("admin.layouts.layout") 
@section("title","Testmonials") 
@section("subtitle","Add") 
@section("css")
@endsection
 
@section("breadcumps")
  <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
  <li><a href="{{route('admin.contents.testmonial.index')}}">Testmonials</a></li>
  <li class="active">Add</li>
@endsection
 
@section("content")
<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <!-- Default box -->
    <a href="{{route('admin.contents.testmonial.index')}}">
      <i class="fa fa-angle-double-left"></i> Back to all  <span class="text-lowercase">Testmonials</span>
    </a><br /><br />

    <form method="POST" action="{{route('admin.contents.testmonial.store')}}" enctype="multipart/form-data" accept-charset="UTF-8">
      @csrf
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Add a new testmonial</h3>
        </div>
        <!-- input field row -->
        <div class="box-body form-horizontal">

          <!-- owner -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Owner *</label>
            <div class="col-sm-10">
              <input type="text" name="owner" value="{{old('owner')}}" placeholder="Owner" class="form-control {{ $errors->has('owner') ? 'is-invalid' : '' }}" />
              @if ($errors->has("owner"))
                <span class="invalid-feedback">{{ $errors->first("owner") }}</span> 
              @endif
            </div>
          </div>
          <!--./ owner -->

          <!-- content -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Content</label>
            <div class="col-sm-10">
              <textarea rows="5" name="content" placeholder="Content" 
                class="form-control {{ $errors->has('content') ? 'is-invalid' : '' }}">{{old('content')}}</textarea>
              @if ($errors->has("content"))
                <span class="invalid-feedback">{{ $errors->first("content") }}</span>
              @endif
            </div>
          </div>
          <!--./ content -->

          <!-- description -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Description *</label>
            <div class="col-sm-10">
              <input type="text" name="description" value="{{old('description')}}" placeholder="Description" class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" />
              @if ($errors->has("description"))
                <span class="invalid-feedback">{{ $errors->first("description") }}</span> 
              @endif
            </div>
          </div>
          <!--./ description -->

          <!-- Image -->
          <div class="form-group" style="margin-top: 10px;">
            <label class="col-sm-2 control-label">Image *</label>
            <div class="col-sm-4">
              <input type="file" id="image" name="image" value="{{old('image')}}" accept="image/*"
                class="form-control {{ $errors->has('image') ? 'is-invalid' : '' }}"/>
              @if ($errors->has("image"))
                <span class="invalid-feedback">{{ $errors->first("image") }}</span>
              @endif
            </div>
            <div class="col-sm-6">
              <img id="thumb-icon" src="data:image/gif;base64,R0lGODlhAQABAAAAACwAAAAAAQABAAA=" accept=".png" alt="thumbnail"/>
            </div>
          </div>
          <!--./ Image -->
        </div>
        <!--./ input field row -->
        <div class="box-footer">
          <div id="saveActions" class="form-group">
            <input type="hidden" name="save_action" value="save_and_back" />
            <div class="btn-group">
              <button type="submit" class="btn btn-success">
                <span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;
                <span data-value="save_and_back">Save and back</span>
              </button>
            </div>
            <a href="{{route('admin.contents.testmonial.index')}}" class="btn btn-default"><span class="fa fa-ban"></span> &nbsp;Cancel</a>
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
        var imgInput = $('#image');
        imgInput.replaceWith(imgInput.val('').clone(true));
      }
    }
}

$("#image").change(function(){
  readURL(this);
});
</script>
@endsection