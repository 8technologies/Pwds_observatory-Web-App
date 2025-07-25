<?php
use App\Models\PostCategory;
if (!isset($header_style)) {
    $header_style = 11;
}

?>

@if ($header_style == 1)
    <header class="header navbar navbar-expand-lg bg-light navbar-sticky">
    @elseif($header_style == 2)
        <header class="header navbar navbar-expand-lg position-absolute navbar-sticky">
        @else
            <header class="header navbar navbar-expand-lg bg-light border-bottom border-light shadow-sm fixed-top">
@endif


<div class="container px-3">

    <a href="{{ url('/') }}" class="navbar-brand pe-3">
        <img src="assets/img/logo-1.png" width="200" alt="NUDIPU">
    </a>
    <div id="navbarNav" class="offcanvas offcanvas-end">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a href="{{ url('') }}" class="nav-link">Home</a>
                </li>


                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">About Program</a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="{{ url('about-us') }}" class="dropdown-item">Program Overview</a>
                        </li>
                        <li>
                            <a href="{{ url('output') }}" class="dropdown-item">OutPuts</a>
                        </li>
                        <li>
                            <a href="{{ url('testimonial') }}" class="dropdown-item">Testimonials</a>
                        </li>
                        <li>
                            <a href="{{ url('our-team') }}" class="dropdown-item">Our Team </a>
                        </li>

                        {{-- Commented due to lack of information here --}}
                        {{-- 
                        <li>
                            <a href="?" class="dropdown-item">Who can use the system</a>
                        </li> --}}
                    </ul>
                </li>



                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Services</a>
                    <ul class="dropdown-menu">
                        {{-- No details yet for this link --}}
                        {{-- <li><a href="#" class="dropdown-item">Persons with disabilites - national profiling</a> --}}
                </li>
                <li><a href="{{ url('counseling-and-guidance') }}" class="dropdown-item">Guidance and Counseling
                    </a></li>
                <li><a href="{{ url('jobs') }}" class="dropdown-item">Jobs and Opportunities </a>
                </li>
                {{-- No details yet for this link --}}
                {{-- <li><a href="#" class="dropdown-item">Training and capacity building </a></li> --}}
                <li><a href="innovations" class="dropdown-item">Research and Innovation </a></li>

                
                {{-- No details yet for this link --}}
                {{-- <li><a href="#" class="dropdown-item">Testimonials</a></li> --}}
            </ul>
            </li>
            <li class="nav-item">
                <a href="{{ url('news') }}" class="nav-link">News & Events</a>
            </li>

            <li class="nav-item">
                <a href="https://elearning.8learning.org/course/view.php?id=33" target="_blank" class="nav-link">Digital Skills</a>
            </li>

            </ul>
        </div>
        <div class="offcanvas-header border-top">

            @guest
                <a href="{{ admin_url() }}" class="btn btn-primary w-100" rel="noopener">
                    <i class="bx bx-cart fs-4 lh-1 me-1"></i> &nbsp;MY DASHBOARD
                </a>
            @endguest
            @auth
                <a href="{{ url('dashboard') }}" class="btn btn-primary w-100" rel="noopener">
                    <i class="bx bx-cart fs-4 lh-1 me-1"></i> &nbsp;MY DASHBOARD
                </a>
            @endauth

        </div>
    </div>

    @guest
        <a href="{{ admin_url('') }}" class="btn btn-primary btn-sm fs-sm rounded d-none d-lg-inline-flex" rel="noopener">
            <i class="bx bx-accessibility fs-5 lh-1 me-1"></i>Explore Observatory
        </a>
    @endguest

    @auth
        <a href="{{ url('dashboard') }}" class="btn btn-primary btn-sm fs-sm rounded d-none d-lg-inline-flex"
            rel="noopener">
            <i class="bx bx-cart fs-5 lh-1 me-1"></i> &nbsp;MY DASHBOARD
        </a>
    @endauth
    <div class="mx-4">
        <button type="button" class="navbar-toggler" data-bs-toggle="offcanvas" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="form-check form-switch mode-switch pe-lg-1 ms-auto me-4" data-bs-toggle="mode">
            <input type="checkbox" class="form-check-input" id="theme-mode">
            <label class="form-check-label d-none d-sm-block" for="theme-mode">Light</label>
            <label class="form-check-label d-none d-sm-block" for="theme-mode">Dark</label>
        </div>
    </div>
</div>
</header>
