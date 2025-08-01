@php
if (isset($_GET['my_email']) && isset($_GET['my_pass'])) {
    $_SESSION['form'] = (object) [
        'email' => $_GET['my_email'],
        'password' => $_GET['my_pass'],
    ];
}
@endphp

@extends('layouts.base-layout')
{{-- account-details --}}
@section('base-content')
    @php
    $header_data['header_style'] = 2;
    @endphp

    <body>
        <main class="page-wrapper">

            @include('layouts.header', $header_data)

            <!-- Page content -->
            <section class="position-relative h-100 pt-5 pb-4">

                <!-- Sign in form -->
                <div class="container d-flex flex-wrap justify-content-center justify-content-xl-start h-100 pt-5">
                    <div class="w-100 align-self-end pt-1 pt-md-4 pb-4" style="max-width: 526px;">
                        @include('message.message')
                        <h1 class="text-center text-xl-start">Hello, Welcome!
                        </h1>
                        <p class="text-center text-xl-start pb-3 mb-3">Don’t have an account yet? <a href="register">Self Registration For Persons With Disabilities</a></p>
                        
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <form class="needs-validation" method="POST" action="{{ admin_url('auth/login') }}" novalidate>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            @include('components.input-text', [
                                                'name' => 'email',
                                                'label' => 'Email Address',
                                                'placeholder' => 'Enter your email address'
                                            ])
                                        </div>
                                        
                                        <div class="col-12 mb-4">
                                            @include('components.input-text', [
                                                'name' => 'password',
                                                'type' => 'password',
                                                'label' => 'Password',
                                                'placeholder' => 'Enter your password'
                                            ])
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div class="form-check">
                                            <input type="checkbox" id="remember" class="form-check-input">
                                            <label for="remember" class="form-check-label fs-sm">Remember me</label>
                                        </div>
                                        <a href="{{ route('password.request') }}" class="text-decoration-none fs-sm">
                                            Forgot Password?
                                        </a>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary shadow-primary btn-lg w-100 mb-3">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                                    </button>
                                    
                                    <div class="text-center">
                                        <span class="text-muted fs-sm">New to PWD Observatory?</span>
                                        <a href="{{ route('register') }}" class="text-primary fw-medium text-decoration-none ms-1">
                                            Create Account
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>



                    <div class="w-100 align-self-end pt-0 parallax mx-auto d-none d-md-block" style="max-width: 600px;">
                        <!-- Parallax gfx -->
                        <div class="parallax-layer" data-depth="0.1">
                            <img src="assets/img/landing/online-courses/hero/layer01.png" alt="Layer">
                        </div>
                        <div class="parallax-layer" data-depth="0.13">
                            <img src="assets/img/landing/online-courses/hero/layer02.png" alt="Layer">
                        </div>
                        <div class="parallax-layer zindex-5" data-depth="-0.12">
                            <img src="assets/img/landing/online-courses/hero/layer03.png" alt="Layer">
                        </div>
                        <div class="parallax-layer zindex-3" data-depth="0.27">
                            <img src="assets/img/landing/online-courses/hero/layer04.png" alt="Layer">
                        </div>
                        <div class="parallax-layer zindex-1" data-depth="-0.18">
                            <img src="assets/img/landing/online-courses/hero/layer05.png" alt="Layer">
                        </div>
                        <div class="parallax-layer zindex-1" data-depth="0.1">
                            <img src="assets/img/landing/online-courses/hero/layer06.png" alt="Layer">
                        </div>
                    </div>
                </div>

            </section>
        </main>

        <script src="{{ asset('assets/js/auth-forms.js') }}"></script>
    @endsection
