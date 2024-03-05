@extends('layouts.layout-main')

@section('main-content')
    <!-- Breadcrumb -->
    <nav class="container mt-5   pt-5" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="{{ url('') }}"><i class="bx bx-home-alt fs-lg me-1"></i>Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ url('service-providers/') }}">Service Providers</a>
            </li>
            {{-- <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($service_provider->title, 20) }}</li> --}}
        </ol>
    </nav>

    <section class="container mb-5 pt-md-4">
        <div class="row">
            @foreach ($service_providers as $service_provider)
                <!-- Item -->
                <div class="col-sm-4 h-auto pb-3">
                    <article class="card border-0 shadow-sm h-100 mx-2">
                        @if ($service_provider->logo)
                            <div class="  position-relative bg-position-center bg-repeat-0 bg-size-cover"
                                style="background-image: url({{ url('storage/' . $service_provider->logo) }}); min-height: 15rem;">
                            @else
                                <div class="  position-relative b   g-position-center bg-repeat-0 bg-size-cover"
                                    style="background-image: url({{ url('assets/img/service_provider_placeholder_alt.png') }}); min-height: 15rem;">
                        @endif

                        </div>
                <div class="card-body pb-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <a href="{{ url('service-providers/' . $service_provider->id) }}"
                            class="badge fs-sm text-nav bg-secondary text-decoration-none">{{ Str::limit($service_provider->name, 40) }}</a>
                        <span class="fs-sm text-muted"></span>
                    </div>
                    <h3 class="h5 mb-0">
                        <a href="{{ url('service-providers/' . $service_provider->id) }}"
                            title="{{ Str::limit($service_provider->name, 40) }}">
                            {!! Str::limit($service_provider->services_offered, 60) !!}</a>
                    </h3>
                </div>
                </article>
        </div>
        @endforeach
        </div>

        <!-- Pagination bootstrap -->
        <div class="row">
            {!! $service_providers->links() !!}
        </div>

        </div>
    </section>
@endsection
