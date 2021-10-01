<header class="main-header">
    <!-- Logo -->
    <a href="{{route('admin.home')}}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>H</b>C</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">Health</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

        <div class="navbar-custom-menu" style="background-color: #2c7dcc">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="height: 50px">
                        {{$currentUser->email}}
                        <span class="hidden-xs">{{$currentUser->username}}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header" style="height: auto; background-color: #2c7dcc">
                            <p>
                                {{$currentUser->username}} - Administrator
                                <small>Member since {{date("d-m-Y",strtotime($currentUser->created_at))}}</small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-right">
                                <a href="{{route('admin.auth.logout')}}" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>