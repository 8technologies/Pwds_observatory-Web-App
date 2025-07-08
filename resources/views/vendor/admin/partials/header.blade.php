<?php
use App\Models\Utils;
use App\Models\User;

?>
<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="{{ admin_url('/') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">{!! 'Observatory' !!}</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg" style="font-weight: bold; color: #ffffff;">{!! 'Observatory' !!}</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top d-block p-0" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <ul class="nav navbar-nav hidden-sm visible-lg-block">
            {!! Admin::getNavbar()->render('left') !!}
        </ul>

        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav ">

                {!! Admin::getNavbar()->render() !!}

                <!-- Chat Toggle Icon -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ admin_url('chat') }}">
                            <i class="bi bi-chat-text-fill"></i>
                            <span class="navbar-badge badge text-bg-danger">4</span>
                        </a>
                        
                </li>
               
                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <img src="{{ Admin::user()->avatar }}" class="user-image" alt="User Image">
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">{{ Admin::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <img src="{{ Admin::user()->avatar }}" class="img-circle" alt="User Image">

                            <p>
                                {{ Admin::user()->name }}
                                <small>Member since admin {{ Admin::user()->created_at }}</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                @php
                                    $user = User::find(Auth::user()->id); 
                                @endphp

                                @if (Auth::user()->isRole('district-union'))
                                    <a href="{{ admin_url('district-unions/' . $user->organisation->id . '/edit') }}"
                                        class="btn btn-default btn-flat">Profile</a>
                                @elseif(Auth::user()->isRole('opd'))
                                    <a href="{{ admin_url('opds/' . $user->organisation->id . '/edit') }}"
                                        class="btn btn-default btn-flat">Profile</a>
                                @elseif(Auth::user()->isRole('service-provider'))
                                    <a href="{{ admin_url('service-providers/' . $user->service_provider . '/edit') }}"
                                        class="btn btn-default btn-flat">Profile</a>
                                @else
                                    <a href="{{ admin_url('auth/setting') }}"
                                        class="btn btn-default btn-flat">Profile</a>
                                @endif
                            </div>


                            <div class="pull-right">
                                <a href="{{ admin_url('auth/logout') }}"
                                    class="btn btn-default btn-flat">{{ trans('admin.logout') }}</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                {{-- <li> --}}
                {{-- <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a> --}}
                {{-- </li> --}}
            </ul>
        </div>

    </nav>
</header>
