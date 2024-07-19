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

            <div class="card card-style">
                <div class="card-body">
                    <h4>{{ $job->title }}</h4>
                    <p><strong>Location:</strong> <span class="fw-bold">{{ $job->location }}</span>
                    </p>
                    <p>
                        <strong>Hiring Firm: </strong>
                        <span style="color: green">{{ $job->hiring_firm }}</span>
                    </p>
                    <p><strong>Type:</strong> <span class="fw-bold">{{ $job->type }}</span></p>
                    <p><strong>Created Date:</strong> <span class="fw-bold">{{ $job->created_at->format('Y-m-d') }}</span>
                    </p>
                    <p><strong>Deadline:</strong> <span class="fw-bold">{{ $job->deadline }}</span>
                    </p>

                    <p><strong>Job Description: </strong><br>{!! $job->description !!}</p>
                    <p>
                        <strong>Required Experience: </strong>
                        <span>{{ $job->required_experience }}</span>
                    </p>
                    <p>
                        <strong>How to apply: </strong>
                        <span>{!! $job->how_to_apply !!}</span>
                    </p>
                </div>
            </div>
            </a>


        </div>
    </section>
@endsection
