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
        <div class="row mb-3">
            <div class="col-md-12 text-center">
                <form action="{{ route('counseling_search') }}" method="GET"
                    class="form-inline d-flex justify-content-center">
                    <div class="form-group mx-2">
                        <input class="form-control" type="search" name="name_search" placeholder="Search by Centre Name"
                            aria-label="Search" value="{{ request('name_search') }}">
                    </div>
                    <div class="form-group mx-2">
                        <select class="form-control" name="disability_search">
                            <option value="">Select Disability Category</option>
                            @foreach ($disabilities as $disability)
                                <option value="{{ $disability->name }}"
                                    {{ request('disability_search') == $disability->name ? 'selected' : '' }}>
                                    {{ $disability->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mx-2">
                        <select class="form-control" name="district_search">
                            <option value="">Select District</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district->name }}"
                                    {{ request('district_search') == $district->name ? 'selected' : '' }}>
                                    {{ $district->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-outline-success mx-2" type="submit">Search</button>
                </form>
            </div>
        </div>

        <div class="row">
            @foreach ($counseling_centres as $centre)
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">{{ $centre->name }}</h5>
                            @if ($centre->disabilities->isNotEmpty())
                                <p class="card-text"><strong>Disability Names:</strong>
                                    @foreach ($centre->disabilities as $disability)
                                        {{ $disability->name }}@if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </p>
                            @endif

                            @if ($centre->address)
                                <p class="card-text"><strong>Address:</strong> {{ $centre->address }}</p>
                            @endif

                            @if ($centre->village)
                                <p class="card-text"><strong>Village:</strong> {{ $centre->village }}</p>
                            @endif

                            @if ($centre->phone_number)
                                <p class="card-text"><strong>Phone Number:</strong> {{ $centre->phone_number }}</p>
                            @endif

                            @if ($centre->email)
                                <p class="card-text"><strong>Email:</strong> {{ $centre->email }}</p>
                            @endif

                            @if ($centre->disabilities->isNotEmpty())
                                <p class="card-text"><strong>Districts Covered:</strong>
                                    @foreach ($centre->districts as $district)
                                        {{ $district->name }}@if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </p>
                            @endif

                            @if ($centre->website)
                                <p class="card-text"><strong>Website:</strong> <a href="{{ $centre->website }}"
                                        target="_blank">{{ $centre->website }}</a></p>
                            @endif
                            <p class="card-text"><small class="text-muted">Created At: {{ $centre->created_at }}</small>
                            </p>
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
