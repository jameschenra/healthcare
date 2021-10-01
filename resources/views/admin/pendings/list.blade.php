@extends("admin.layouts.layout")
@section("title","Pending Plans")
@section("subtitle","List")
@section("css")
  <link rel="stylesheet" href="{{ asset('public/adminTemplate/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection
 
@section("breadcumps")
  <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
  <li class="active">Pending Plans</li>
@endsection
 
@section("content")
<div class="box box-primary">
  <div class="box-body">
    <table id="usersTable" class="table table-bordered table-striped display dt-responsive nowrap dataTable dtr-inline">
      <thead>
        <tr>
          <th>Primary Member</th>
          <th>Member</th>
          <th>Relationship</th>
          <th>Plan</th>
          <th>Price</th>
          <th>Expires In</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($plans as $plan)
        <tr>
          <td>{{ $plan->owner->userInfo->first_name }} {{ $plan->owner->userInfo->last_name }}</td>
          <td>{{ $plan->user->userInfo->first_name }} {{ $plan->user->userInfo->last_name }}</td>
          <td>{{ $plan->user->relationship }}</td>
          <td>{{ $plan->planType->name }}</td>
          <td>$ {{ $plan->total_price }}</td>
          <td>{{ $plan->expires_in }}</td>
          <td>
            <form action="{{route('admin.pendings.delete', ['id' => $plan->id])}}" method="post" class="deleteForm">
              @csrf

              <input name="_method" type="hidden" value="DELETE">
              <a href="{{route('admin.pendings.approve',['id' => $plan->id])}}" class="btn btn-xs btn-primary">
                <i class="fa fa-edit"></i> Approve
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