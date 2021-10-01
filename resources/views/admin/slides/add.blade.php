@extends("admin.layouts.layout") 
@section("title","Slides") 
@section("subtitle","Add") 
@section("css")
@endsection
 
@section("breadcumps")
  <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
  <li><a href="{{route('admin.slides')}}">Slides</a></li>
  <li class="active">Add</li>
@endsection
 
@section("content")
<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <!-- Default box -->
    <a href="{{route('admin.slides')}}">
      <i class="fa fa-angle-double-left"></i> Back to all  <span class="text-lowercase">slides</span>
    </a><br /><br />

    <form method="POST" action="{{route('admin.slides.store')}}" enctype="multipart/form-data" accept-charset="UTF-8">
      @csrf
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Add a new slide</h3>
        </div>
        <!-- input field row -->
        <div class="box-body form-horizontal">
          <!-- title -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Title *</label>
            <div class="col-sm-10">
              <input type="text" name="title" value="{{old('title')}}" placeholder="Title" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" />
              @if ($errors->has("title"))
                <span class="invalid-feedback">{{ $errors->first("title") }}</span> 
              @endif
            </div>
          </div>
          <!--./ title -->
          <!-- sub title -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Sub Title *</label>
            <div class="col-sm-10">
              <input type="text" name="sub_title" value="{{old('sub_title')}}" placeholder="Sub Title" class="form-control {{ $errors->has('sub_title') ? 'is-invalid' : '' }}" />
              @if ($errors->has("sub_title"))
                <span class="invalid-feedback">{{ $errors->first("sub_title") }}</span>
              @endif
            </div>
          </div>
          <!--./ sub title -->
          <!-- order -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Order</label>
            <div class="col-sm-10">
              <select class="form-control" name="order">
                <option value="{{ $maxOrder + 1 }}"></option>
                @for ($i = 1; $i <= $maxOrder; $i++)
                  <option value="{{ $i }}">{{ $i }}</option>
                @endfor
              </select>
            </div>
          </div>
          <!--./ order -->
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
            <a href="{{route('admin.slides')}}" class="btn btn-default"><span class="fa fa-ban"></span> &nbsp;Cancel</a>
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