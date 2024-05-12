<?php
use App\Models\PostCategory;
use App\Models\NewsPost;
use App\Models\Utils;
if (!isset($header_style)) {
    $header_style = 11;
}
?>
<style>
    .pagination {
        font-size: 2rem;

    }

    .pagination a:hover {
        background-color: rgb(55, 162, 224);
        color: white;
    }

    /* Example CSS */
    .pagination .page-item.active .page-link {
        background-color: rgb(55, 162, 224);
        color: white;
    }
</style>
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
        <div class="row">
            <div class="col-md-12 text-center">
                <h3 class="all-jobs mb-4"><span class="text-primary">View Available Jobs</span></h3>
            </div>
        </div>
        @foreach ($jobs as $job)
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card job-card">
                        <div class="card-body">
                            <h4>{{ $job->title }}</h4>
                            <p><strong>Location:</strong> <span class="fw-bold">{{ $job->location }}</span></p>
                            <p><strong>Type:</strong> <span class="fw-bold">{{ $job->type }}</span></p>
                            <p><strong>Created Date:</strong> <span
                                    class="fw-bold">{{ $job->created_at->format('Y-m-d') }}</span></p>
                            <p><strong>Deadline:</strong> <span class="fw-bold">{{ $job->deadline }}</span></p>

                            <div id="short_{{ $job->id }}" class="text">
                                {!! Str::limit($job->description, 400) !!}
                                <span onclick="expandText('{{ $job->id }}')"
                                    class="read-more text-primary">...........Read More</span>
                            </div>

                            <div id="full_{{ $job->id }}" class="text full-text" style="display: none;">
                                {!! $job->description !!}
                                <span onclick="collapseText('{{ $job->id }}')"
                                    class="read-less text-primary">........Read Less</span>
                            </div>

                            {{-- <div id="short_{{ $job->id }}" class="text">
                                <p class="card-text">{{ Str::limit($job->description, 400) }}</p>
                                <span onclick="expandText('{{ $job->id }}')" class="read-more text-primary">Read
                                    More</span>
                            </div>

                            <div id="full_{{ $job->id }}" class="text full-text" style="display: none;">
                                <p class="card-text">{!! $job->description !!}</p>
                                <span onclick="collapseText('{{ $job->id }}')" class="read-less text-primary">Read
                                    Less</span>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
            <hr>
        @endforeach
        <div class="row">
            <div class="col-md-12">
                {{ $jobs->links('pagination::simple-bootstrap-4') }}
            </div>
        </div>
    </div>
    </div>
    <!-- Pagination (bullets) -->
    </section>
@endsection

<script>
    function expandText(id) {
        var shortText = document.getElementById('short_' + id);
        var fullText = document.getElementById('full_' + id);
        shortText.style.display = 'none';
        fullText.style.display = 'block';
    }

    function collapseText(id) {
        var shortText = document.getElementById('short_' + id);
        var fullText = document.getElementById('full_' + id);
        shortText.style.display = 'block';
        fullText.style.display = 'none';
    }
</script>
