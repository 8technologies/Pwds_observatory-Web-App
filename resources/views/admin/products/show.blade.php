<div class="container bg-white p-1 p-md-5">
    <div class="d-md-flex justify-content-between">
        <div>
            {{-- <h2 class="m-0 p-0 text-dark h3 text-uppercase"><b>Suspect {{ ' - ' . $pwd->uwa_suspect_number  '-' }}</b> --}}
            </h2>
        </div>
        <div class="mt-3 mt-md-0">
            @isset($_SERVER['HTTP_REFERER'])
                <a href="{{ $_SERVER['HTTP_REFERER'] }}" class="btn btn-secondary btn-sm"><i class="fa fa-chevron-left"></i>
                    BACK
                    TO ALL LIST</a>
            @endisset
            <a href="{{ admin_url('products/' . $product->id . '/edit') }}" class="btn btn-warning btn-sm"><i
                    class="fa fa-edit"></i>
                EDIT</a>
            <a href="#" onclick="window.print();return false;" class="btn btn-primary btn-sm"><i
                    class="fa fa-print"></i> PRINT</a>
        </div>
    </div>
    <hr class="my-3 my-md-4">
    <div class="row">
        <div class="col-3 col-md-2">
            <div class="border border-1 rounded bg-">
                @if ($product->photo == null)
                    <img class="img-fluid" src="{{ asset('assets/img/user-1.png') }}" width="250" height="500">
                @else
                    <img class="img-fluid" src="{{ asset('storage/' . $product->photo) }}" width="250"
                        height="500">
                @endif
            </div>
        </div>
        <div class="col-9 col-md-10">
            <h3 class="text-uppercase h4 p-0 m-0"><b>ABOUT THE PRODUCT</b></h3>
            <hr class="my-1 my-md-3">

            @include('components.detail-item', [
                't' => 'Name',
                's' => $product->name,
            ])

            @include('components.detail-item', [
                't' => 'Service Provider',
                's' => $product->serviceProvider->name,
            ])
            @include('components.detail-item', ['t' => 'Offer Type', 's' => $product->offer_type])


        </div>

    </div>

    <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
    <h3 class="text-uppercase h4 p-0 m-0"><b>Product Details</b></h3>
    <hr class="m-0 pt-0">

    <ul>
        <p>{{ strip_tags($product->details) }}</p>
    </ul>

    {{-- <h3 class="text-uppercase h4 p-0 m-0"><b>Offer Type</b></h3>
    <hr class="m-0 pt-0"> --}}
    <hr class="m-0 pt-0">
    @if ($product->offer_type == 'hire')
        <h3>Hire Descriptions</h3>
        <p>{{ $product->hire_descriptions }}</p>
    @endif

    @if ($product->offer_type == 'sale')
        <h3>Price</h3>
        <p>Ugsh {{ $product->price }}</p>
    @endif

    {{-- <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
    <h3 class="text-uppercase h4 p-0 m-0 text-center"><b>ACADEMIC QUALIFACTION</b></h3>
    <hr class="m-0 pt-0 mb-3">
    <table class="table table-bordered table-striped table-hover">
        <tr class="text-bold">
            <td>Institution</td>
            <td>Qualification</td>
            <td>Year Of Completion</td>
        </tr>

        @foreach ($pwd->academic_qualifications as $record)
            <tr>
                <td>{{ $record->institution }}</td>
                <td>{{ $record->qualification }}</td>
                <td>{{ $record->year_of_completion }}</td>
            </tr>
        @endforeach

    </table>

    @if ($pwd->is_employed == 1)
        <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
        <h3 class="text-uppercase h4 p-0 m-0 text-center"><b>Current Employment</b></h3>
        <hr class="m-0 pt-0">
        <table class="table table-bordered table-striped table-hover">
            <tr class="text-bold">
                <td>Name</td>
                <td>Position</td>
                <td>Duration</td>
            </tr>
            <tr>
                <td>{{ $pwd->employer }}</td>
                <td>{{ $pwd->position }}</td>
                <td>{{ $pwd->year_of_employment }}</td>
            </tr>

        </table>
    @endif

    @if ($pwd->is_formerly_employed)

        <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
        <h3 class="text-uppercase h4 p-0 m-0 text-center"><b>Previous Employment</b></h3>
        <hr class="m-0 pt-0 mb-3">
        <table class="table table-bordered table-striped table-hover">
            <tr class="text-bold">
                <td>Name</td>
                <td>Position</td>
                <td>Duration</td>
            </tr>

            @foreach ($pwd->employment_history as $record)
                <tr>
                    <td>{{ $record->employer }}</td>
                    <td>{{ $record->position }}</td>
                    <td>{{ $record->year_of_employment }}</td>
                </tr>
            @endforeach

        </table>

    @endif --}}


</div>
<style>
    .content-header {
        display: none;
    }
</style>
