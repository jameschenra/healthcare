@extends("admin.layouts.layout")
@php

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
    <form method="POST" action="{{route('admin.contents.wkhours.update')}}">

      @csrf

      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Edit</h3>
        </div>
        <div class="box-body form-horizontal">

          <div class="form-group row">
            <div class="col-sm-3 text-center">Week Day</div>
            <div class="col-sm-3 text-center">Start Time</div>
            <div class="col-sm-3 text-center">End Time</div>
            <div class="col-sm-2 text-center">Open Status</div>
          </div>

          @foreach($workingHours as $index=>$item)
            <div class="form-group row">
              <label class="col-sm-3 text-center">{{ $item->week_day }}</label>
              <div class="col-sm-3 text-center">
                <input type="time" name="start[]" value="{{ $item->start }}" placeholder="start time" />
              </div>
              <div class="col-sm-3 text-center">
                <input type="time" name="end[]" value="{{ $item->end }}" placeholder="start time" />
              </div>
              <div class="col-sm-2 text-center">
                <input type="checkbox" name="open_status[]" value="{{$index}}" {{$item->open_status == 1 ? 'checked': ''}}/>
              </div>
            </div>
          @endforeach
          

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