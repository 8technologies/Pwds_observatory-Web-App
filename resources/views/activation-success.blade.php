@extends('layouts.base-layout')
@section('base-content')
@php $header_data['header_style'] = 2; @endphp

<main class="page-wrapper">
    @include('layouts.header', $header_data)

    <section class="position-relative h-100 pt-5 pb-4">
        <div class="container d-flex justify-content-center align-items-center h-100 pt-5">
            <div class="text-center" style="max-width: 500px;">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="bi bi-check-lg text-white" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        
                        <h2 class="text-success mb-3">Account Activated!</h2>
                        
                        <p class="text-muted mb-4">
                            Congratulations! Your account has been successfully activated with the PWD Observatory. 
                            You can now access all features and begin your journey with us.
                        </p>
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login to Your Account
                            </a>
                            <a href="{{ url('/') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-house me-2"></i>Back to Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
