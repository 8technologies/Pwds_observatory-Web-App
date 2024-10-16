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
        font-size: 3rem;

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

    .card-body-fixed {
        width: 100%;
        height: 500px;
        overflow: auto;
        padding: 10px;
        margin-bottom: 10px;
    }

    .card_job {
        color: rgb(71, 67, 67);
    }


    .card-object {
        margin-bottom: 20px;
        width: 100%;
    }
</style>
@extends('layouts.layout-main')
@section('main-content')

    <section class="position-relative pt-5">

        <!-- Background -->
        <div class="position-absolute top-0 start-0 w-100 bg-position-bottom-center bg-size-cover bg-repeat-0 top-lining">

            <div class="d-none d-lg-block" style="height: 178px;"></div>
        </div>


        <!-- Post title + Meta  -->
        <div class="container position-relative zindex-5 pt-5">
            <div class="row">
                <div class="col-lg-6">
                    <!-- Breadcrumb -->
                    <nav class="pt-md-2 pt-lg-3 pb-4 pb-md-5 mb-xl-4" aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ url('') }}"><i class="bx bx-home-alt fs-lg me-1"></i>Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Jobs & Opportunities</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                {{-- <div class="col-md-12 text-center">
                <h3 class="all-jobs mb-4"><span class="text-primary">View Available Jobs</span></h3>
            </div> --}}
                <div class="col-md-12 text-center">
                    <form action="{{ route('job_search') }}" method="GET"
                        class="form-inline d-flex justify-content-center">
                        <div class="form-group mx-2">
                            <input class="form-control" type="search" name="title_search"
                                placeholder="Search by title/profession" aria-label="Search"
                                value="{{ request('title_search') }}">
                        </div>
                        <div class="form-group mx-2">
                            <input class="form-control" type="search" name="location_search"
                                placeholder="Search by location" aria-label="Search"
                                value="{{ request('location_search') }}">
                        </div>
                        <button class="btn btn-outline-success mx-2" type="submit">Search</button>
                    </form>
                </div>
            </div>
            {{-- Filtering Jobs that have not expired yet --}}
            @php
                $activeJobs = $jobs->filter(function ($job) {
                    return $job->status === 'Active';
                });
            @endphp

            @if ($activeJobs->isEmpty())
                <p>No jobs available.</p>
            @else
                <div class="container jobs-display">
                    <div class="row">
                        @foreach ($activeJobs as $job)
                            <div class="col-md-4 card-object">
                                <a href="{{ url('jobs/' . $job->id) }}" class="text-decoration-none card_job">
                                    <div class="card card-style">
                                        <div class="card-body-fixed">
                                            <h4>{{ $job->title }}</h4>
                                            <p><strong>Location:</strong> <span class="fw-bold">{{ $job->location }}</span>
                                            </p>
                                            <p>
                                                <strong>Hiring Firm: </strong>
                                                <span style="color: green">{{ $job->hiring_firm }}</span>
                                            </p>
                                            <p><strong>Type:</strong> <span class="fw-bold">{{ $job->type }}</span></p>
                                            <p><strong>Created Date:</strong> <span
                                                    class="fw-bold">{{ $job->created_at->format('Y-m-d') }}</span></p>
                                            <p><strong>Deadline:</strong> <span class="fw-bold">{{ $job->deadline }}</span>
                                            </p>
                                            @php
                                                $description = strip_tags($job->description);
                                                $shortDescription = Str::limit($description, 400);
                                            @endphp
                                            <div id="short_{{ $job->id }}" class="text">
                                                {!! $shortDescription !!}
                                                @if (str_word_count($description) > 200)
                                                    <span onclick="expandText('{{ $job->id }}')"
                                                        class="read-more text-primary">...........Read More</span>
                                                @endif
                                            </div>

                                            <div id="full_{{ $job->id }}" class="text full-text"
                                                style="display: none;">
                                                {!! $job->description !!}
                                                <span onclick="collapseText('{{ $job->id }}')"
                                                    class="read-less text-primary">........Read Less</span>
                                            </div>
                                            <p>
                                                <strong>Required Experience: </strong><br>
                                                <span>{{ $job->required_experience }}</span>
                                            </p>
                                            <p>
                                                <strong>How to apply: </strong><br>
                                                <span>{!! $job->how_to_apply !!}</span>
                                            </p>
                                        </div>
                                    </div>
                                </a>

                            </div>
                        @endforeach
                    </div>
                </div>

                <hr>
                {{-- {{ $jobs->links() }} --}}
            @endif

            <div class="row">
                <div class="col-md-4">
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
