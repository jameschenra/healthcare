@php
    $user = Auth::user();
@endphp
<aside class="main-sidebar">
    <!-- sidebar Start: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu Start: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">Applications</li>
            <li {{Utils::checkMenuActive('admin.home')}}>
                <a href="{{ route("admin.home") }}">
                    <i class="fa fa-th"></i> <span>Dashboard</span>
                </a>
            </li>

            <li {{Utils::checkMenuActive('admin.users')}}>
                <a href="{{ route("admin.users") }}">
                    <i class="fa fa-th"></i> <span>Memberships</span>
                </a>
            </li>

            {{-- <li {{Utils::checkMenuActive('admin.pendingmembers')}}>
                <a href="{{ route("admin.pendingmembers") }}">
                    <i class="fa fa-th"></i> <span>Pending Membership</span>
                </a>
            </li> --}}

            <li class="treeview {{Utils::checkMenuOpen('admin.membertrack')}}">
                <a href="#">
                    <i class="fa fa-book"></i> <span>Membership Track</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li {{Utils::checkMenuActive('admin.membertrack.admin')}}>
                        <a href="{{ route("admin.membertrack.admin") }}">
                            <i class="fa fa-th"></i> <span>By Admin</span>
                        </a>
                    </li>
                    <li {{Utils::checkMenuActive('admin.membertrack.user')}}>
                        <a href="{{ route("admin.membertrack.user") }}">
                            <i class="fa fa-th"></i> <span>By User</span>
                        </a>
                    </li>
                </ul>
            </li>
            
            @if ($user->role_id == 1)
                <li {{Utils::checkMenuActive('admin.adminusers')}}>
                    <a href="{{ route("admin.adminusers.index") }}">
                        <i class="fa fa-th"></i> <span>Admin Users</span>
                    </a>
                </li>

                <li {{Utils::checkMenuActive('admin.slides')}}>
                    <a href="{{ route("admin.slides") }}">
                        <i class="fa fa-th"></i> <span>Slides</span>
                    </a>
                </li>

                <li {{Utils::checkMenuActive('admin.memberplantypes')}}>
                    <a href="{{ route("admin.memberplantypes") }}">
                        <i class="fa fa-th"></i> <span>Membership Plan Types</span>
                    </a>
                </li>

                <li {{Utils::checkMenuActive('admin.discounts')}}>
                    <a href="{{ route("admin.discounts.index") }}">
                        <i class="fa fa-th"></i> <span>Discounts</span>
                    </a>
                </li>

                <li class="treeview {{Utils::checkMenuOpen('admin.contents')}}">
                    <a href="#">
                        <i class="fa fa-book"></i> <span>Contents</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li {{Utils::checkMenuActive('admin.contents.contact.edit')}}>
                            <a href="{{ route("admin.contents.contact.edit") }}">
                                <i class="fa fa-th"></i> <span>Contact Info</span>
                            </a>
                        </li>
                        <li {{Utils::checkMenuActive('admin.contents.mission.edit')}}>
                            <a href="{{ route("admin.contents.mission.edit") }}">
                                <i class="fa fa-th"></i> <span>Mission</span>
                            </a>
                        </li>
                        <li {{Utils::checkMenuActive('admin.contents.vision.edit')}}>
                            <a href="{{ route("admin.contents.vision.edit") }}">
                                <i class="fa fa-th"></i> <span>Vision</span>
                            </a>
                        </li>
                        <li {{Utils::checkMenuActive('admin.contents.value.edit')}}>
                            <a href="{{ route("admin.contents.value.edit") }}">
                                <i class="fa fa-th"></i> <span>Value</span>
                            </a>
                        </li>
                        <li {{Utils::checkMenuActive('admin.contents.testmonial')}}>
                            <a href="{{ route("admin.contents.testmonial.index") }}">
                                <i class="fa fa-th"></i> <span>Testimonials</span>
                            </a>
                        </li>
                        <li {{Utils::checkMenuActive('admin.contents.wkhours.edit')}}>
                            <a href="{{ route("admin.contents.wkhours.edit") }}">
                                <i class="fa fa-th"></i> <span>Working Hours</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
            
            <li class="treeview {{Utils::checkMenuOpen('admin.pendings')}}">
                <a href="#">
                    <i class="fa fa-book"></i> <span>Payments</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li {{Utils::checkMenuActive('admin.pendings')}}>
                        <a href="{{ route("admin.pendings.index") }}">
                            <i class="fa fa-th"></i> <span>Pending Plans</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
        <!--/. sidebar menu End: -->
    </section>
    <!-- /.sidebar End-->
</aside>