@extends("admin.layouts.layout")
@section("title","Slides")
@section("subtitle","List")
@section("css")
  <link rel="stylesheet" href="{{ asset('public/adminTemplate/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection
 
@section("breadcumps")
  <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
  <li class="active">Slides</li>
@endsection
 
@section("content")
<div class="box box-primary">
  <div class="box-header with-border">
    <a href="{{route('admin.slides.create')}}" class="btn btn-primary ladda-button" data-style="zoom-in">
      <span class="ladda-label">
        <i class="fa fa-plus"></i> Add slide
      </span>
    </a>
  </div>
  <div class="box-body">
    <table id="dataTable" class="table table-bordered table-striped display dt-responsive nowrap dataTable dtr-inline">
      <thead>
        <tr>
          <th>ID</th>
          <th>Image</th>
          <th>Title</th>
          <th>Sub Title</th>
          <th>Order</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($slides as $slide)
        <tr>
          <td>{{$slide->id}}</td>
          <td><img id="thumb-icon" src="{{ asset('public/files/slides') . '/' . $slide->file_name}}" alt="icon photo"/></td>
          <td>{{$slide->title}}</td>
          <td>{{$slide->sub_title}}</td>
          <td>{{$slide->order}}</td>
          <td>
            <form action="{{route('admin.slides.destroy', ['id' => $slide->id])}}" method="post" class="deleteForm">
              @csrf
              <input name="_method" type="hidden" value="DELETE">
              <a href="{{route('admin.slides.edit',["id " => $slide->id])}}" class="btn btn-xs btn-primary">
                <i class="fa fa-edit"></i> Edit
              </a>
              <button type="button" class="btn btn-xs btn-danger" data-button-type="delete" onclick="onDel(event)">
                <i class="fa fa-trash"></i>Delete
              </button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
 
@section("js")
<script src="{{ asset('public/adminTemplate/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/adminTemplate/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script>
  $("#dataTable").DataTable();

  function onDel(event) {
    event.preventDefault();
    $obj = $(event.target);
    if (confirm('Are you sure delete this membership?')) {
      $obj.closest('form').submit();
    }
  }
</script>
@endsection