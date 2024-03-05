@extends('layouts.layout-main')
@section('main-content')
    <!-- Breadcrumb -->
    <nav class="container mt-5   pt-5" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="{{ url('') }}"><i class="bx bx-home-alt fs-lg me-1"></i>Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ url('disabilities') }}">Disabilities</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($disability->name, 20) }}</li>
        </ol>
    </nav>


    <!-- Post title + Meta  -->
    <section class="container mt-4 pt-lg-2 pb-3">
        <h1 class="pb-3" style="max-width: 970px;">{{ $disability->title }}</h1>
        <div class="d-flex flex-md-row flex-column align-items-md-center justify-content-md-between mb-3">
            <div class="d-flex align-items-center flex-wrap text-muted mb-md-0 mb-4">
                <div class="fs-xs border-end pe-3 me-3 mb-2">
                    <span
                        class="badge bg-faded-primary text-primary fs-base">{{ Str::limit($disability->name, 20) }}</span>
                </div>
                <div class="fs-sm border-end pe-3 me-3 mb-2">{{ $disability->people()->count() }} People Recorded </div>
             
            </div>
        
        </div>
    </section>


    <!-- Post image (parallax) -->
    <div class="jarallax d-lg-none mb-lg-5 mb-4" data-jarallax data-speed="0.35"
        style="height: 36.45vw; min-height: 300px;">
        <div class="jarallax-img" style="background-image: url({{ url('storage/' . $disability->photo) }});"></div>
    </div>


    <!-- Post content + Sharing -->
    <section class="container mb-5 pt-4 pt-lg-0 pb-2 py-mg-4">
        <div class="row gy-4">

            <!-- Content -->
            <div class="col-lg-9">

                <img class="img-fluid rounded mb-3 d-none d-lg-block" src="{{ url('storage/' . $disability->photo) }}"
                    alt="">

                <h3 class="h5 mb-4 pb-2 fw-medium">{{ $disability->name }}</h3>

                {!! $disability->description !!}
            </div>
        </div>
    </section>
@endsection
