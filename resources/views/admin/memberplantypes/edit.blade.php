@extends("admin.layouts.layout")
@php
  $name = $plan->name;
  $adult_price = old("adult_price", $plan->adult_price);
  $child_price = old("child_price", $plan->child_price);
  $desc_first = old("desc_first", $plan->desc_first);
  $desc_second = old("desc_second", $plan->desc_second);
  $desc_third = old("desc_third", $plan->desc_third);
@endphp
@section("title","Membership Plans") 
@section("subtitle","Edit") 
 
@section("breadcumps")
  <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
  <li><a href="{{route('admin.memberplantypes')}}">Membership Plans</a></li>
  <li class="active">Edit</li>
@endsection

@section("content")
<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <!-- Default box -->
    <a href="{{route('admin.memberplantypes')}}">
      <i class="fa fa-angle-double-left"></i> Back to all<span class="text-lowercase"></span>
    </a><br /><br />
    <form method="POST" action="{{route('admin.memberplantypes.update', ['id' => $plan->id])}}">
      @csrf
      <input name="_method" type="hidden" value="PUT" />
      <input name="id" type="hidden" value="{{$plan->id}}" />
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Edit</h3>
        </div>
        <div class="box-body form-horizontal">
          <!-- plan name -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Plan Name</label>
            <div class="col-sm-10">
              <input type="text" name="name" value="{{ $name }}" placeholder="Plan Name" class="form-control" readonly/>
            </div>
          </div>
          <!--./ plan name -->

          <!-- adult price -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Adult Price *</label>
            <div class="col-sm-10">
              <input type="text" name="adult_price" value="{{ $adult_price }}" placeholder="Adult Price"
                class="form-control {{ $errors->has('adult_price') ? 'is-invalid' : '' }}" />
              @if ($errors->has("adult_price"))
                <span class="invalid-feedback">{{ $errors->first("adult_price") }}</span>
              @endif
            </div>
          </div>
          <!--./ adult price -->

          <!-- child price -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Child Price *</label>
            <div class="col-sm-10">
              <input type="text" name="child_price" value="{{ $child_price }}" placeholder="Child Price"
                class="form-control {{ $errors->has('child_price') ? 'is-invalid' : '' }}" />
              @if ($errors->has("child_price"))
                <span class="invalid-feedback">{{ $errors->first("child_price") }}</span>
              @endif
            </div>
          </div>
          <!--./ child price -->

          <!-- first description -->
          <div class="form-group">
            <label class="col-sm-2 control-label">First Description</label>
            <div class="col-sm-10">
              <input type="text" name="desc_first" value="{{ $desc_first }}" placeholder="Description"
                class="form-control {{ $errors->has('desc_first') ? 'is-invalid' : '' }}" />
              @if ($errors->has("desc_first"))
                <span class="invalid-feedback">{{ $errors->first("desc_first") }}</span>
              @endif
            </div>
          </div>
          <!--./ first description -->

          <!-- second description -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Second Description</label>
            <div class="col-sm-10">
              <input type="text" name="desc_second" value="{{ $desc_second }}" placeholder="Description"
                class="form-control {{ $errors->has('desc_second') ? 'is-invalid' : '' }}" />
              @if ($errors->has("desc_second"))
                <span class="invalid-feedback">{{ $errors->first("desc_second") }}</span>
              @endif
            </div>
          </div>
          <!--./ second description -->

          <!-- third description -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Third Description</label>
            <div class="col-sm-10">
              <input type="text" name="desc_third" value="{{ $desc_third }}" placeholder="Description"
                class="form-control {{ $errors->has('desc_third') ? 'is-invalid' : '' }}" />
              @if ($errors->has("desc_third"))
                <span class="invalid-feedback">{{ $errors->first("desc_third") }}</span>
              @endif
            </div>
          </div>
          <!--./ third description -->

        </div>

        <div class="box-footer">
          <div id="saveActions" class="form-group">
            <div class="btn-group">
              <button type="submit" class="btn btn-success">
                <span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;
                <span data-value="save_and_back">Save and back</span>
              </button>
            </div>
            <a href="{{route('admin.memberplantypes')}}" class="btn btn-default">
              <span class="fa fa-ban"></span> &nbsp;Cancel
            </a>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
 
@section("js")

@endsection