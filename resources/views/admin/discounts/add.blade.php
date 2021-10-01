@extends("admin.layouts.layout") 
@section("title","Discounts") 
@section("subtitle","Add") 
@section("css")
@endsection
 
@section("breadcumps")
  <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
  <li><a href="{{route('admin.discounts.index')}}">Discounts</a></li>
  <li class="active">Add</li>
@endsection
 
@section("content")
<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <!-- Default box -->
    <a href="{{route('admin.discounts.index')}}">
      <i class="fa fa-angle-double-left"></i> Back to all  <span class="text-lowercase">discounts</span>
    </a><br /><br />

    <form method="POST" action="{{route('admin.discounts.store')}}">
      @csrf
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Add a new discount</h3>
        </div>

        <!-- input field row -->
        <div class="box-body form-horizontal">

          <!-- name -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Name *</label>
            <div class="col-sm-10">
              <input type="text" name="name" value="{{old('name')}}" placeholder="Name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" />
              @if ($errors->has("name"))
                <span class="invalid-feedback">{{ $errors->first("name") }}</span> 
              @endif
            </div>
          </div>
          <!--./ name -->

          <!-- value -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Value *</label>
            <div class="col-sm-10">
              <input type="text" name="value" value="{{old('value')}}" placeholder="Value" class="form-control {{ $errors->has('value') ? 'is-invalid' : '' }}" />
              @if ($errors->has("value"))
                <span class="invalid-feedback">{{ $errors->first("value") }}</span>
              @endif
            </div>
          </div>
          <!--./ value -->

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
            <a href="{{route('admin.discounts.index')}}" class="btn btn-default"><span class="fa fa-ban"></span> &nbsp;Cancel</a>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
 
@section("js")
@endsection