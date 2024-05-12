<?php
use App\Models\PostCategory;
use App\Models\NewsPost;
use App\Models\Utils;
if (!isset($header_style)) {
    $header_style = 11;
}
?>
@extends('layouts.layout-main')
@section('main-content')
    <!-- Breadcrumb -->
    <nav class="container mt-5   pt-5" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="{{ url('') }}"><i class="bx bx-home-alt fs-lg me-1"></i>Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ url('services') }}">Services</a>
            </li>
            {{-- <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($c->title, 20) }}</li> --}}
        </ol>
    </nav>


    <!-- Post title + Meta  -->

    <div class="container">
        <h1 class="mt-5 mb-4 text-center">Counselling Centres</h1>
        <div class="row">
            @foreach ($counselingCentres as $centre)
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">{{ $centre->name }}</h5>
                            <p class="card-text"><strong>Disability Name:</strong> {{ $centre->disability_name }}</p>
                            <p class="card-text"><strong>Address:</strong> {{ $centre->address }}</p>
                            <p class="card-text"><strong>Village:</strong> {{ $centre->village }}</p>
                            <p class="card-text"><strong>Phone Number:</strong> {{ $centre->phone_number }}</p>
                            <p class="card-text"><strong>Email:</strong> {{ $centre->email }}</p>
                            <p class="card-text"><strong>District Name:</strong> {{ $centre->district_name }}</p>
                            <p class="card-text"><strong>Website:</strong> <a href="{{ $centre->website }}"
                                    target="_blank">{{ $centre->website }}</a></p>
                            <p class="card-text"><small class="text-muted">Created At: {{ $centre->created_at }}</small>
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Pagination (bullets) -->
    <div class="swiper-pagination position-relative pt-2 pt-sm-3 mt-4"></div>
    </div>
    </section>
@endsection
