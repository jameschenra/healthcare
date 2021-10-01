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
                        <th scope="col">User</th>
                        <th scope="col">Price</th>
                        <th scope="col">Payment Type</th>
                        <th scope="col">Paid Day</th>
                        <th scope="col">Expires</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                        <tr>
                            <td>{{ $payment->user_name }}</th>
                            <td>${{ $payment->price }}</th>
                            <td>{{ $payment->payment_type }}</td>
                            <td>{{ $payment->created_at }}</td>
                            <td>{{ $payment->expires_in }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section("js")

@endsection