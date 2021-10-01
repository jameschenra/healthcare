@extends("admin.layouts.layout")
@section("title","Member Tracks")
@section("subtitle","List")
@section("css")
  <link rel="stylesheet" href="{{ asset('public/adminTemplate/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection
 
@section("breadcumps")
  <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
  <li class="active">Member Tracks By Admin</li>
@endsection
 
@section("content")
<div class="box box-primary">
  <div class="box-body">
    <table id="dataTable" class="table table-bordered table-striped display dt-responsive nowrap dataTable dtr-inline">
      <thead>
        <tr>
          <th>User Name</th>
          <th>Relationship</th>
          <th>Primary Member</th>
          <th>Plan</th>
          <th>Expire Date</th>
          <th>Created At</th>
        </tr>
      </thead>
      <tbody class="text-center">
        @foreach($memberTracks as $track)
          <tr>
            <td>{{ $track->user_name }}</td>
            <td>{{ $track->relationship }}</td>
            <td>{{ $track->owner_name }}</td>
            <td>{{ $track->plan }}</td>
            <td>{{ $track->expires_in }}</td>
            <td>{{ $track->created_at }}</td>
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
  $("#dataTable").DataTable({
    "order": [[ 4, "asc" ]]
  });
</script>
@endsection