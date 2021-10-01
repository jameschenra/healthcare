@php
    $isPrimary = Utils::isPrimary();
@endphp
<style>
    ul.side-menu {
        list-style:none;
        width: 100%;
        margin: 0;
        padding: 0;
    }

    ul.side-menu li {
        margin-bottom: 2px;
        background-color: #222222;
        font-weight: 600;
    }

    ul.side-menu li.active {
        background-color: #888;
    }

    ul.side-menu li:hover {
        background-color: #555;
    }

    ul.side-menu li a {
        color: #ffe;
        padding: 17px 13px;
        display: block;
    }
</style>
<div class="col-md-2 d-flex align-items-stretch">
    <ul class="side-menu">
        <li {{Utils::checkMenuActive('user.profile')}}>
            <a href="{{ route('user.profile.index') }}">My Information</a>
        </li>
        @if ($isPrimary)
            <li {{Utils::checkMenuActive('user.membership.')}}>
                <a href="{{ route('user.membership.plans') }}">Add Member</a>
            </li>
            <li {{Utils::checkMenuActive('user.pendings.')}}>
                <a href="{{ route('user.pendings.index') }}">Pending Memberships</a>
            </li>
        @endif
        
        <li {{Utils::checkMenuActive('user.members.')}}>
            <a href="{{ route('user.members.index') }}">Purchased Memberships</a>
        </li>

        @if ($isPrimary)
            <li {{Utils::checkMenuActive('user.payments')}}>
                <a href="{{ route('user.payments.history') }}">Payment History</a>
            </li>
        @endif
    </ul>
</div>