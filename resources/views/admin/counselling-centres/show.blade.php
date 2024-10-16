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

    .centre-item {
        font-size: 18px;

    }

    .card-body-fixed {
        width: 100%;
        height: 400px;
        overflow: auto;
        padding: 10px;
        margin-bottom: 5px;
    }

    .card-object {
        margin-bottom: 20px;
        width: 100%;
    }

    .card-title {
        color: #ffffff;
        font-size: 18px;
        background-color: rgb(72, 171, 228);
        margin: 0px;
        padding: 10px;
        border-radius: 5px;
    }

    .card-text {
        font-size: 16px;
    }
</style>


<div class="container">
    <h1 class="mt-5 mb-4 text-center" style="color:rgb(72, 171, 228);">Counselling Centres</h1>
    {{-- <div class="row mb-3">
        <div class="col-md-12 text-center">
            <form action="{{ route('counseling-centre-search') }}" method="GET"
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
    </div> --}}

    @if ($counselingCentres->isEmpty())
        <p class="text-center mt-5">No counselling centres found.</p>
    @else
        <div class="row">
            @foreach ($counselingCentres as $centre)
                <div class="col-md-6">
                    <div class="card mb-4 card-object">
                        <div class="card-body-fixed">
                            <h5 class="card-title" style="font-size: 24px;">{{ ucwords($centre->name) }}</h5>
                            @if ($centre->about)
                                <p class="card-text"><strong class="centre-item">About The Centre:
                                    </strong>{{ strip_tags($centre->about) }}
                                </p>
                            @endif
                            @if ($centre->disabilities->isNotEmpty())
                                <p class="card-text"><strong class="centre-item">Disability Names:</strong>
                                    @foreach ($centre->disabilities as $disability)
                                        {{ $disability->name }}@if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </p>
                            @endif

                            @if ($centre->address)
                                <p class="card-text"><strong class="centre-item">Address:</strong>
                                    {{ $centre->address }}</p>
                            @endif

                            @if ($centre->village)
                                <p class="card-text"><strong class="centre-item">Village:</strong>
                                    {{ $centre->village }}</p>
                            @endif

                            @if ($centre->phone_number)
                                <p class="card-text"><strong class="centre-item">Phone Number:</strong>
                                    {{ $centre->phone_number }}</p>
                            @endif

                            @if ($centre->email)
                                <p class="card-text"><strong class="centre-item">Email:</strong> {{ $centre->email }}
                                </p>
                            @endif

                            @if ($centre->disabilities->isNotEmpty())
                                <p class="card-text"><strong class="centre-item">Districts Covered:</strong>
                                    @foreach ($centre->districts as $district)
                                        {{ $district->name }}@if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </p>
                            @endif

                            @if ($centre->website)
                                <p class="card-text"><strong class="centre-item">Website:</strong> <a
                                        href="{{ $centre->website }}" target="_blank">{{ $centre->website }}</a></p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{-- {{ $counselingCentres->links() }} --}}
    @endif

    <div class="row">
        <div class="col-md-4">
            {{ $counselingCentres->links('pagination::simple-bootstrap-4') }}
        </div>
    </div>
</div>

<!-- Pagination (bullets) -->
<div class="swiper-pagination position-relative pt-2 pt-sm-3 mt-4"></div>
</div>
