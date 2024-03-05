@extends('layouts.layout-main')
@section('main-content')
    <!-- Breadcrumb -->
    <nav class="container mt-5   pt-5" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="{{ url('') }}"><i class="bx bx-home-alt fs-lg me-1"></i>Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ url('service-providers') }}">Service Providers</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($service_provider->name, 20) }}</li>
        </ol>
    </nav>


    <!-- Post title + Meta  -->
    <section class="container mt-4 pt-lg-2 pb-3">
        <h1 class="pb-3" style="max-width: 970px;">{{ $service_provider->name }}</h1>
        <div class="d-flex flex-md-row flex-column align-items-md-center justify-content-md-between mb-3">
            <div class="d-flex align-items-center flex-wrap text-muted mb-md-0 mb-4">
                <div class="fs-xs border-end pe-3 me-3 mb-2">
                    <span
                        class="badge bg-faded-primary text-primary fs-base">{{ Str::limit($service_provider->name, 20) }}</span>
                </div>
             
            </div>
        
        </div>
    </section>


    <!-- Post image (parallax) -->
    <div class="jarallax d-lg-none mb-lg-5 mb-4" data-jarallax data-speed="0.35"
        style="height: 36.45vw; min-height: 300px;">
        @if ($service_provider->logo != null)
            <div class="jarallax-img" style="background-image: url({{ asset('storage/' . $service_provider->logo) }});"></div>
        @else
            <div class="jarallax-img" style="background-image: url({{ asset('assets/img/service_provider_placeholder_alt.png') }});"></div>
        @endif
    </div>


    <!-- Post content + Sharing -->
    <section class="container mb-5 pt-4 pt-lg-0 pb-2 py-mg-4">
        <div class="row gy-4">

            <!-- Content -->
            <div class="col-lg-9">
                @if($service_provider->logo != null)
                <img class="img-fluid rounded mb-3 d-none d-lg-block" src="{{ url('storage/' . $service_provider->logo) }}"
                    alt="">
                @else
                <img class="img-fluid rounded mb-3 d-none d-lg-block" src="{{ url('assets/img/service_provider_placeholder_alt.png') }}"
                    alt="">
                @endif

                <h3 class="h5 mb-4 pb-2 fw-medium">{{ $service_provider->name }}</h3>
                <h6 class="h6 mb-4 pb-2 fw-medium">{{ $service_provider->physical_address }}</h6>
                <h6 class="h6 mb-4 pb-2 fw-medium">{{ $service_provider->telephone }}</h6>
                <h6 class="h6 mb-4 pb-2 fw-medium">{{ $service_provider->email }}</h6>
                <h6 class="h6 mb-4 pb-2 fw-medium">{{ $service_provider->post_address }}</h6>
                <hr class="my-4">
                <h3 class="h5 mb-4 pb-2 fw-medium">Services Offered</h3>
                <p class="fs-lg mb-4">
                    {!! $service_provider->services_offered !!}
                </p>
                <hr class="my-4">
                <h3 class="h5 mb-4 pb-2 fw-medium">Level of Operation</h3>
                <p class="fs-lg mb-4">
                    {!! $service_provider->level_of_operation !!}
                </p>
                <hr class="my-4">
                <h3 class="h5 mb-4 pb-2 fw-medium">Target Group</h3>
                <p class="fs-lg mb-4">
                    {!! $service_provider->target_group !!}
                </p>
                <hr class="my-4">
                <h3 class="h5 mb-4 pb-2 fw-medium">Disability Category</h3>
                <p class="fs-lg mb-4">
                    {!! $service_provider->disability_category !!}
                <hr class="my-4">
                <h3 class="h5 mb-4 pb-2 fw-medium">Districts of Operation</h3>
                <p class="fs-lg mb-4">
                    {!! $service_provider->districts_of_operation !!}
                </p>
                <hr class="my-4">


                {!! $service_provider->brief_profile !!}
            </div>
        </div>
    </section>
@endsection
