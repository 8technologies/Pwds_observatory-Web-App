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
        margin-bottom: 5px;
    }

    .card-title {
        color: #ffffff;
        font-size: 18px;
        background-color: rgb(72, 171, 228);
        margin: 0px;
        padding: 10px;
        border-radius: 5px;
    }

    .card-object {
        margin-bottom: 20px;
        width: 100%;
    }
</style>

<!-- Post title + Meta  -->
<div class="container">
    <h1 class="mt-5 mb-4 text-center" style="color:rgb(72, 171, 228);">Jobs Portal</h1>
    {{-- <div class="row"> --}}
    {{-- <div class="col-md-12 text-center">
                <h3 class="all-jobs mb-4"><span class="text-primary">View Available Jobs</span></h3>
            </div> --}}
    {{-- <div class="col-md-12 text-center">
            <form action="{{ route('job_search') }}" method="GET" class="form-inline d-flex justify-content-center">
                <div class="form-group mx-2">
                    <input class="form-control" type="search" name="title_search" placeholder="Search by title/profession"
                        aria-label="Search" value="{{ request('title_search') }}">
                </div>
                <div class="form-group mx-2">
                    <input class="form-control" type="search" name="location_search" placeholder="Search by location"
                        aria-label="Search" value="{{ request('location_search') }}">
                </div>
                <button class="btn btn-outline-success mx-2" type="submit">Search</button>
            </form>
        </div> --}}
    {{-- </div> --}
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
                        <div class="card card-style">
                            <div class="card-body-fixed">
                                <h4 class="card-title">{{ $job->title }}</h4>
                                <p><strong>Location:</strong> <span class="fw-bold">{{ $job->location }}</span></p>
                                <p>
                                    <strong>Hiring Firm: </strong>
                                    <span style="color: green">{{ $job->hiring_firm }}</span>
                                </p>
                                <p><strong>Type:</strong> <span class="fw-bold">{{ $job->type }}</span></p>
                                <p><strong>Created Date:</strong> <span
                                        class="fw-bold">{{ $job->created_at->format('Y-m-d') }}</span></p>
                                <p><strong>Deadline:</strong> <span class="fw-bold">{{ $job->deadline }}</span></p>
                                {{-- <p>
                                    <strong>Days to Close:</strong>
                                    <span style="color:red">{{ $job->days_remaining }} days remaining</span>
                                </p> --}}
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

                                <div id="full_{{ $job->id }}" class="text full-text" style="display: none;">
                                    {!! $job->description !!}
                                    <span onclick="collapseText('{{ $job->id }}')"
                                        class="read-less text-primary">........Read Less</span>
                                </div>
                                <p>
                                    <strong>Required Experience: </strong>
                                    <span>{{ $job->required_experience }}</span>
                                </p>
                                <p>
                                    <strong>How to apply: </strong>
                                    <span> {{ strip_tags($job->how_to_apply) }} </span>
                                </p>
                            </div>
                        </div>
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

<div class="swiper-pagination position-relative pt-2 pt-sm-3 mt-4"></div>
</div>



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
