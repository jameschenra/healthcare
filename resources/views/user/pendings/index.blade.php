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
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Plan</th>
                        <th scope="col">Relationship</th>
                        <th scope="col">Monthly</th>
                        <th scope="col">Yearly</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $member)
                        <tr>
                            <th scope="row">{{ $member->first_name }} {{ $member->last_name }}</th>
                            <td>
                                {{ $member->plan_name }}
                            </td>
                            <td>{{ $member->relationship}}</td>
                            <td>$ {{ $member->plan_price }}</td>
                            <td>$ {{ $member->plan_price * 12 }}</td>
                        </tr>
                        <?php $totalPrice += $member->plan_price * 12?>
                    @endforeach
                </tbody>
            </table>
            <hr />
        </div>
    </div>
@endsection

@section("js")
<script>
    $(function(){
        $('.btn-delete').on('click', function(e){
            var bConfirm = confirm("Are you sure delete pending membership?");
            if (bConfirm == true) {
                $(this).closest('form').submit();
            }
        });
    });
</script>
@endsection