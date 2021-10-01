@extends("admin.layouts.layout")
@section("title","Admin Users")
@section("subtitle","List")
@section("css")
  <link rel="stylesheet" href="{{ asset('public/adminTemplate/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection
 
@section("breadcumps")
  <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
  <li class="active">Admin Users</li>
@endsection
 
@section("content")
<div class="box box-primary">
  <div class="box-header with-border">
    <a href="{{route('admin.adminusers.create')}}" class="btn btn-primary ladda-button" data-style="zoom-in">
      <span class="ladda-label">
        <i class="fa fa-plus"></i> Add user
      </span>
    </a>
  </div>
  <div class="box-body">
    <table id="usersTable" class="table table-bordered table-striped display dt-responsive nowrap dataTable dtr-inline">
      <thead>
        <tr>
          <th>Number</th>
          <th>Email</th>
          <th>Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $index => $user)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td>{{ $user->email }}</td>
          <td>{{ $user->created_at }}</td>
          <td>
            <form action="{{route('admin.adminusers.destroy', ['id' => $user->id])}}" method="post" class="deleteForm">
              {{csrf_field()}}
              <input name="_method" type="hidden" value="DELETE">
              <a href="{{route('admin.adminusers.edit',["id " => $user->id])}}" class="btn btn-xs btn-primary">
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
  $("#usersTable").DataTable();

  function onDel(event) {
    event.preventDefault();
    $obj = $(event.target);
    if (confirm('Are you sure delete this membership?')) {
      $obj.closest('form').submit();
    }
  }
</script>
@endsection