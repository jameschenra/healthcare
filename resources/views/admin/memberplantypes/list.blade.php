@extends("admin.layouts.layout")
@section("title","Membership Plans")
@section("subtitle","List")
@section("css")
  <link rel="stylesheet" href="{{ asset('public/adminTemplate/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection
 
@section("breadcumps")
  <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
  <li class="active">Membership Plans</li>
@endsection
 
@section("content")
<div class="box box-primary">
  <div class="box-body">
    <table id="dataTable" class="table table-bordered table-striped display dt-responsive nowrap dataTable dtr-inline">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Adult Price</th>
          <th>Child Price</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($memberPlanTypes as $plan)
        <tr>
          <td>{{$plan->id}}</td>
          <td>{{$plan->name}}</td>
          <td>{{$plan->adult_price}}</td>
          <td>{{$plan->child_price}}</td>
          <td>
            <a href="{{route('admin.memberplantypes.edit',["id " => $plan->id])}}" class="btn btn-xs btn-primary">
              <i class="fa fa-edit"></i> Edit
            </a>
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
</script>
@endsection