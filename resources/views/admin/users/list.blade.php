@php
  $topLineStyle = 'border-top: 1px solid darkgray';
@endphp

@extends("admin.layouts.layout")
@section("title","Pending Memberships")
@section("subtitle","List")
@section("css")
  <link rel="stylesheet" href="{{ asset('public/adminTemplate/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection
 
@section("breadcumps")
  <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
  <li class="active">Memberships</li>
@endsection
 
@section("content")
<div class="box box-primary">
  <div class="box-header with-border">
    <a href="{{route('admin.users.create')}}" class="btn btn-primary ladda-button" data-style="zoom-in">
      <span class="ladda-label">
        <i class="fa fa-plus"></i> Add membership
      </span>
    </a>
  </div>
  <div class="box-body">
    <table id="usersTable" class="table table-bordered table-striped display dt-responsive nowrap dataTable dtr-inline">
      <thead>
        <tr>
          <th>Member ID</th>
          <th>Relationship</th>
          <th>Primary Member</th>
          <th>Email</th>
          <th>Username</th>
          <th>Plan Type</th>
          <th>Status</th>
          <th>Actions</th>
          <th>Checkout</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $user)
          <tr>
            <td style="{{ $user->relationship == 'Primary' ? $topLineStyle : ''}}">{{ $user->membership_number }}</td>
            <td style="{{ $user->relationship == 'Primary' ? $topLineStyle : ''}}">{{ $user->relationship }}</td>
            <td style="{{ $user->relationship == 'Primary' ? $topLineStyle : ''}}">{{ $user->owner_email }}</td>
            <td style="{{ $user->relationship == 'Primary' ? $topLineStyle : ''}}">{{ $user->email }}</td>
            <td style="{{ $user->relationship == 'Primary' ? $topLineStyle : ''}}">{{ $user->first_name }} {{$user->last_name}}</td>
            <td style="{{ $user->relationship == 'Primary' ? $topLineStyle : ''}}">{{ $user->plan_name }}</td>
            <td style="{{ $user->relationship == 'Primary' ? $topLineStyle : ''}}">
              @if($user->status)
                {{ Utils::MEMBERSTATUS[$user->status - 1] }}
              @else
                {{ $user->pending_status == 2 ? 'Offline Pending' : 'Pending' }}
              @endif
              
            </td>
            <td style="{{ $user->relationship == 'Primary' ? $topLineStyle : ''}}">
              <form action="{{route('admin.users.destroy', ['id' => $user->id])}}" method="post" class="deleteForm">
                {{csrf_field()}}
                <input name="_method" type="hidden" value="DELETE">
                <a href="{{route('admin.users.edit',["id " => $user->id])}}" class="btn btn-xs btn-primary">
                  <i class="fa fa-edit"></i> Edit
                </a>
                <a href="{{route('admin.users.print.edit',["id " => $user->id])}}" 
                  class="btn btn-xs btn-primary{{$user->membership_number ? '' : ' isDisabled'}}">
                  <i class="fa fa-print"></i> Print
                </a>
                <button type="button" class="btn btn-xs btn-danger" data-button-type="delete" onclick="onDel(event)">
                  <i class="fa fa-trash"></i>Delete
                </button>
              </form>
            </td>
            <td style="{{ $user->relationship == 'Primary' ? $topLineStyle : ''}}">
              @if($user->relationship == 'Primary')
                {{-- <a href="{{route('admin.users.print.edit',["id " => $user->id])}}"  --}}
                <a href="{{ route('admin.payment.pendinglist',["id" => $user->id]) }}" 
                  class="btn btn-xs btn-primary">
                  <i class="fa fa-check"></i> Checkout
                </a>
              @endif
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
  $("#usersTable").DataTable({
    "order": [[ 2, "desc" ]]
  });

  function onDel(event) {
    event.preventDefault();
    $obj = $(event.target);
    if (confirm('Are you sure delete this membership?')) {
      $obj.closest('form').submit();
    }
  }
</script>
@endsection