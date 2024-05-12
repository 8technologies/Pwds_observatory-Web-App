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

        <h1 class="text-center mb-4">Innovations</h1>
        <div class="row">
            @foreach ($innovations as $innovation)
                <div class="col-md-6 mb-4">
                    <div class="card">
                        {{-- <img src="{{ $innovation->photo }}" class="card-img-top" alt="{{ $innovation->title }}"> --}}
                        <div class="card-body">
                            <h5 class="card-title">{{ $innovation->title }}</h5>
                            <p class="card-text"><strong>Type:</strong> {{ $innovation->innovation_type }}</p>
                            <p class="card-text"><strong>Status:</strong> {{ $innovation->innovation_status }}</p>
                            <p class="card-text"><strong>Innovators:</strong>
                                @foreach ($innovation->innovators as $innovator)
                                    {{ $innovator['name'] }},
                                @endforeach
                            </p>
                            <p class="card-text"><strong>Created At:</strong> {{ $innovation->created_at }}</p>
                            <p class="card-text">{!! strip_tags($innovation->description) !!}</p>
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
