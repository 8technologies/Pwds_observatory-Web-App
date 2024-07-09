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
            <a href="{{ admin_url('products/' . $event->id . '/edit') }}" class="btn btn-warning btn-sm"><i
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
                @if ($event->photo == null)
                    <img class="img-fluid" src="{{ asset('assets/img/user-1.png') }}" width="250" height="500">
                @else
                    <img class="img-fluid" src="{{ asset('storage/' . $event->photo) }}" width="250" height="500">
                @endif
            </div>
        </div>
        <div class="col-9 col-md-10">
            <h3 class="text-uppercase h4 p-0 m-0"><b>ABOUT THE EVENT</b></h3>
            <hr class="my-1 my-md-3">

            @include('components.detail-item', [
                't' => 'Title',
                's' => $event->title,
            ])

            @include('components.detail-item', [
                't' => 'Venue',
                's' => $event->venue_name,
            ])
            @include('components.detail-item', ['t' => 'Date', 's' => $event->event_date])


        </div>

    </div>

    <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
    <h3 class="text-uppercase h4 p-0 m-0"><b>Theme</b></h3>
    <hr class="m-0 pt-0">
    <ul>
        <p>{{ $event->theme }}</p>
    </ul>
    <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
    <h3 class="text-uppercase h4 p-0 m-0"><b>Event Details</b></h3>
    <hr class="m-0 pt-0">

    <p style="font-size: 20px;">{{ strip_tags($event->details) }}</p>

    <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
    @if ($event->event_map_photo)
        <h3 class="text-uppercase h4 p-0 m-0"><b>Event Map</b></h3>
        <hr class="m-0 pt-0">
        <p>{{ $event->event_map_photo }}</p>
    @endif


    @if ($event->video)
        <h3 class="text-uppercase h4 p-0 m-0"><b>Event Video</b></h3>
        <hr class="m-0 pt-0">
        <p>{{ $event->video }}</p>
    @endif
    <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
    @if ($event->number_of_attendants)
        <p class="card-text"><strong>Number of Attendants:</strong> {{ $event->number_of_attendants }}</p>
    @endif

    @if ($event->number_of_speakers)
        <p class="card-text"><strong>Number of Speakers:</strong> {{ $event->number_of_speakers }}</p>
    @endif

    @if ($event->number_of_experts)
        <p class="card-text"><strong>Number of Experts:</strong> {{ $event->number_of_experts }}</p>
    @endif


</div>
<style>
    .content-header {
        display: none;
    }
</style>
