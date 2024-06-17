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
            {{-- <div class="col-md-12 text-center">
                <h3 class="all-jobs mb-4"><span class="text-primary">View Available Jobs</span></h3>
            </div> --}}
            <div class="col-md-12 text-center">
                <form action="{{ route('job_search') }}" method="GET" class="form-inline d-flex justify-content-center">
                    <div class="form-group mx-2">
                        <input class="form-control" type="search" name="title_search"
                            placeholder="Search by title/profession" aria-label="Search"
                            value="{{ request('title_search') }}">
                    </div>
                    <div class="form-group mx-2">
                        <input class="form-control" type="search" name="location_search" placeholder="Search by location"
                            aria-label="Search" value="{{ request('location_search') }}">
                    </div>
                    <button class="btn btn-outline-success mx-2" type="submit">Search</button>
                </form>
            </div>
        </div>

        @php
            $activeJobs = $jobs->filter(function ($job) {
                return $job->status === 'Active';
            });
        @endphp

        @if ($activeJobs->isEmpty())
            <p>No jobs available.</p>
        @else
            @foreach ($activeJobs as $job)
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card job-card">
                            <div class="card-body">
                                <h4>{{ $job->title }}</h4>
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <p><strong>Location:</strong> <span class="fw-bold">{{ $job->location }}</span></p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><strong>Type:</strong> <span class="fw-bold">{{ $job->type }}</span></p>
                                    </div>
                                    <div class="col-md-3">
                                        <p>
                                            <strong>Hiring Firm: </strong>
                                            <span style="color: green">{{ $job->hiring_firm }}</span>
                                        </p>
                                    </div>
                                    <div class="col-md-3">
                                        <p>
                                            <strong>Status:</strong>
                                            <span style="color: green">{{ $job->status }}</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p><strong>Created Date:</strong> <span
                                                class="fw-bold">{{ $job->created_at->format('Y-m-d') }}</span></p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><strong>Deadline:</strong> <span class="fw-bold">{{ $job->deadline }}</span></p>
                                    </div>
                                    <div class="col-md-3">
                                        <p>
                                            <strong>Days to Close:</strong>
                                            <span style="color:red">{{ $job->days_remaining }} days remaining</span>
                                        </p>
                                    </div>
                                </div>


                                <div id="short_{{ $job->id }}" class="text">
                                    {!! Str::limit($job->description, 400) !!}
                                    <span onclick="expandText('{{ $job->id }}')"
                                        class="read-more text-primary">.....Read More</span>
                                </div>

                                <div id="full_{{ $job->id }}" class="text full-text" style="display: none;">
                                    {!! $job->description !!}
                                    <span onclick="collapseText('{{ $job->id }}')"
                                        class="read-less text-primary">.....Read Less</span>
                                </div>
                                <p>
                                    <strong>Required Experience: </strong>
                                    <span style="color: green">{{ $job->required_experience }}</span>
                                </p>
                                <p>
                                    <strong>How to apply: </strong>
                                    <span style="color: green">{{ $job->how_to_apply }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            @endforeach

            <!-- Pagination Links -->
            <div class="d-flex justify-content-center">
                {{ $jobs->links() }}
            </div>
        @endif
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
