@php
    $totalPrice = 0;    
@endphp
@extends("user.layouts.page_layout")

@section('title', 'Profile Edit')

@section("css")
@endsection

@section("page_content")
    <div class="row d-flex align-items-stretch no-gutters">
        @include('user.layouts.profile_sidebar')
        
        <div class="col-md-10 p-4 p-md-5 p-md-0 order-md-last bg-light">
            <a href="{{ route('user.membership.create') }}" style="width:200px;"class="btn btn-secondary py-3 px-4">Add New Member</a>
            <br /><br />

            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Plan</th>
                        <th scope="col">Relationship</th>
                        <th scope="col">Monthly</th>
                        <th scope="col">Yearly</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $member)
                        <tr>
                            <th scope="row">{{ $member->first_name }} {{ $member->last_name }}</th>
                            <td>
                                {{ $member->plan_name }}
                            </td>
                            <td>{{ $member->relationship }}</td>
                            <td>$ {{ $member->plan_price }}</td>
                            <td>$ {{ $member->plan_price * 12 }}</td>
                            <td>
                                <form action="{{route('user.membership.destroy', [$member->pending_id])}}" method="POST" class="deleteForm">
                                    @if($member->relationship == 'Primary')
                                        <a href="{{ route('user.membership.index') }}"><button type="button">Edit</button></a>
                                    @else
                                        <a href="{{ route('user.membership.edit', [$member->pending_id]) }}"><button type="button">Edit</button></a>
                                    @endif
                                    @csrf
                                    <input name="_method" type="hidden" value="DELETE">
                                    <button type="button" class="btn-delete" style="margin-left:15px;" data-button-type="delete">Remove</button>
                                </form>
                            </td>
                        </tr>
                        <?php $totalPrice += $member->plan_price * 12; ?>
                    @endforeach
                </tbody>
            </table>
            <hr />
            <br />
            <div>
                <div class="float-left">Total Due Today: ${{ $totalPrice }} </div>
                <div class="float-right">
                    <button id="btn-pay" type="button" class="btn btn-secondary">Go To Payment</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("js")
<script>
    var totalPrice = "{{ $totalPrice }}";
    $(function() {
        $('#btn-pay').on('click', function(){
            if (totalPrice && totalPrice > 0) {
                window.location.href= "{{ route('user.payments.index') }}";
            } else {
                alert('Please add new member.');
            }
        });

        $('.btn-delete').on('click', function(e){
            var bConfirm = confirm("Are you sure delete pending membership?");
            if (bConfirm == true) {
                $(this).closest('form').submit();
            }
        });
    });
</script>
@endsection