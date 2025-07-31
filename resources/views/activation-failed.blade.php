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
                            <div class="bg-danger rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="bi bi-exclamation-triangle-fill text-white" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        
                        <h2 class="text-danger mb-3">Activation Failed</h2>
                        
                        <p class="text-muted mb-4">
                            Sorry, we couldn't activate your account. The activation link may be invalid, expired, or already used.
                        </p>
                        
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Need help?</strong> Please contact our support team or try registering again.
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-person-plus me-2"></i>Register Again
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Try Login
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
