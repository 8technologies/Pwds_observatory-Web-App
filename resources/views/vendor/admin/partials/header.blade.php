<?php
use App\Models\Utils;
use App\Models\User;

?>
<style>
/* make sure this appears *after* all Bootstrap/AdminLTE CSS */
.navbar-custom-menu .chat-notification .chat-badge {
  position: absolute;
  top: 0.9rem;
  right: 0.7rem;
  background-color: #dc3545;    /* red */
  color: #fff;
  font-size: 0.9rem;
  line-height: 1;
  min-width: 1.4em;
  height:   1.4em;
  padding:  0 0.3em;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  box-shadow: 0 0 0 1px #fff;   /* thin white outline */
  pointer-events: none;
  user-select: none;
  animation: chatBadgePulse 1.5s ease-in-out infinite;
}

/* pulse/glow animation */
@keyframes chatBadgePulse {
  0% {
    box-shadow: 0 0 0 1px #fff;
    transform: scale(1);
  }
  50% {
    box-shadow: 0 0 8px rgba(220,53,69,0.7);
    transform: scale(1.1);
  }
  100% {
    box-shadow: 0 0 0 1px #fff;
    transform: scale(1);
  }
}



</style>
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
                @php 
                $unread = App\Models\Chat::getAllChatUserCount();
                    @endphp

                    <li class="nav-item chat-notification position-relative">
                    <a class="nav-link" href="{{ admin_url('chat') }}">
                        <i class="bi bi-chat-text-fill"></i>
                        @if($unread > 0)
                        
                        <span class="chat-badge">{{ $unread }}</span>
                        @endif
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
