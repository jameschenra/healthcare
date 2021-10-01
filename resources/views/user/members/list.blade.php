@php
    $isPrimary = Utils::isPrimary();
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
                        <th scope="col">Relationship</th>
                        <th scope="col">Date of Birth</th>
                        <th scope="col">Plan</th>
                        {{-- <th scope="col">Price</th> --}}
                        <th scope="col">Expires In</th>
                        <th scope="col">Status</th>
                        @if ($isPrimary)
                            <th scope="col">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $member)
                        <tr>
                            <th scope="row">{{ $member->first_name }} {{ $member->last_name }}</th>
                            <td>{{ $member->relationship }}</td>
                            <td>{{ $member->birthday }}</td>
                            <td>
                                {{ $member->plan_name }}
                            </td>
                            {{-- <td>$ {{ $member->total_price }}</td> --}}
                            <td>{{ $member->expires_in }}</td>
                            <td>{{Utils::MEMBERSTATUS[$member->status - 1]}}</td>
                            @if ($isPrimary)
                                <td>
                                    @if( $member->status == 4)
                                        <a href="{{ route('user.members.showupgrade', ['id' => $member->id, 'update_type' => 'new']) }}"><button>New</button></a>
                                    @elseif( $member->primary_update == 0 && $member->plan_type_id != 3)
                                        <a href="{{ route('user.members.showupgrade', ['id' => $member->id, 'update_type' => 'upgrade']) }}"><button>Upgrade</button></a>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section("js")

@endsection