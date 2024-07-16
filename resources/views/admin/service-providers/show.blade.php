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

    .service-item {
        /* color: rgb(72, 171, 228); */
        font-size: 18px;
    }

    .card-title {
        color: #ffffff;
        font-size: 18px;
        background-color: rgb(72, 171, 228);
        margin: 0px;
        padding: 10px;
        border-radius: 5px;
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

    .card-text {
        font-size: 16px;
    }
</style>


<div class="container">
    <h1 class="mt-5 mb-4 text-center" style="color:rgb(72, 171, 228);">Service Providers</h1>
    @if ($service_providers->isEmpty())
        <p class="text-center mt-5">No service providers found.</p>
    @else
        <div class="row">
            @foreach ($service_providers as $provider)
                <div class="col-md-6">
                    <div class="card mb-4 card-object">
                        <div class="card-body-fixed">
                            <h5 class="card-title">{{ $provider->name }}
                            </h5>
                            @if ($provider->disability_categories->isNotEmpty())
                                <p class="card-text"><strong class="service-item">Disability Categories:</strong>
                                    @foreach ($provider->disability_categories as $disability)
                                        {{ $disability->name }}@if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </p>
                            @endif

                            @if ($provider->physical_address)
                                <p class="card-text"><strong class="service-item">Address:</strong>
                                    {{ $provider->physical_address }}</p>
                            @endif

                            @if ($provider->email)
                                <p class="card-text"><strong class="service-item">Email:</strong> {{ $provider->email }}
                                </p>
                            @endif

                            @if ($provider->telephone)
                                <p class="card-text"><strong class="service-item">Phone Number:</strong>
                                    {{ $provider->telephone }}</p>
                            @endif

                            @if ($provider->services_offered)
                                <p class="card-text"><strong class="service-item">Services Offered:</strong>
                                    {{ $provider->services_offered }}</p>
                            @endif

                            @if ($provider->districts_of_operations->isNotEmpty())
                                <p class="card-text"><strong class="service-item">Districts Of Operations:</strong>
                                    @foreach ($provider->districts_of_operations as $district)
                                        {{ $district->name }}@if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </p>
                            @endif

                            @if ($provider->level_of_operation)
                                <p class="card-text"><strong class="service-item">Level Of Operation: </strong>
                                    {{ $provider->level_of_operation }}</p>
                            @endif
                            @if ($provider->target_group)
                                <p class="card-text"><strong class="service-item">Target Group:
                                    </strong>{{ $provider->target_group }}</p>
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
            {{ $service_providers->links('pagination::simple-bootstrap-4') }}
        </div>
    </div>
</div>

<!-- Pagination (bullets) -->
<div class="swiper-pagination position-relative pt-2 pt-sm-3 mt-4"></div>
</div>
