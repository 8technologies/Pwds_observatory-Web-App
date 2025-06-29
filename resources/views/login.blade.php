<?php
if (isset($_GET['my_email']) && isset($_GET['my_pass'])) {
    $_SESSION['form'] = (object) [
        'email' => $_GET['my_email'],
        'password' => $_GET['my_pass'],
    ];
}
?>@extends('layouts.base-layout')
{{-- account-details --}}
@section('base-content')
    <?php
    $header_data['header_style'] = 2;
    ?>

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
                        <p class="text-center text-xl-start pb-3 mb-3">Don’t have an account yet? <a href="register"> Register
                                here.</a></p>
                        <form class="needs-validation" method="POST" action="{{ admin_url('auth/login') }}" novalidate>
                        {{-- <form class="needs-validation" method="POST" action="{{ url('just/login') }}" novalidate> --}}
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="row">


                                <div class="col-12 mb-3">

                                    @include('components.input-text', [
                                        'name' => 'email',
                                        'label' => 'Email',
                                    ])

                                </div>
                                <div class="col-12 mb-4">

                                    @include('components.input-text', [
                                        'name' => 'password',
                                        'type' => 'password',
                                        'label' => 'Password',
                                    ])
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="form-check">
                                    <input type="checkbox" id="terms" class="form-check-input">
                                    <label for="terms" class="form-check-label fs-base">Remember me</label>
                                </div>
                            </div>
                            <div class="mb-4">
                                <button type="submit" class="btn btn-primary shadow-primary btn-lg w-100">Sign in</button>
                            </div>
                            <div class="mb-4">
                                <a href="{{ route('password.request') }}" class="btn btn-link">Forgot Password?</a>
                            </div>
                        </form>

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
    @endsection
