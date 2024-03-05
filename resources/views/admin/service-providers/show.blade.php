<div class="container bg-white p-1 p-md-5">
    <div class="d-md-flex justify-content-between">
        <div>
            {{-- <h2 class="m-0 p-0 text-dark h3 text-uppercase"><b>Suspect {{ ' - ' . $service_provider->uwa_suspect_number  '-' }}</b> --}}
            </h2>
        </div>
        <div class="mt-3 mt-md-0 float-left">
            {{-- @if($service_provider->membership_type == 'member') 
                <a class="btn btn-sm btn-primary mx-3" href="{{url('admin/opds/create') }}">Add OPD</a>
                <a class="btn btn-sm btn-info mx-3" href="{{url('admin/district-unions/create') }}">Add District Union</a>
            @elseif($service_provider->membership_type == 'both') 
                <a class="btn btn-sm btn-info mx-3" href="{{url('admin/people/create') }}">Add Person With Disability</a>
                <a class="btn btn-sm btn-primary mx-3" href="{{url('admin/opds/create') }}">Add OPD</a>
                <a class="btn btn-sm btn-info mx-3" href="{{url('admin/district-unions/create') }}">Add District Union</a>
            @else
                <a class="btn btn-sm btn-info mx-3" href="{{url('admin/people/create') }}">Add Person With Disability</a>
            @endif --}}
            @if(!$service_provider->is_verified)
                <a href="{{ admin_url(request()->segment(2) .'/'. $service_provider->id . '/verify') }}" class="btn btn-success btn-sm"><i class="fa fa-check"></i>
                    VERIFY</a>
            @endif 
        </div>
        <div class="mt-3 mt-md-0">
            @isset($_SERVER['HTTP_REFERER'])
                <a href="{{ $_SERVER['HTTP_REFERER'] }}" class="btn btn-secondary btn-sm"><i class="fa fa-chevron-left"></i>
                    BACK
                    TO ALL LIST</a>
            @endisset
            <a href="{{ admin_url(request()->segment(2) .'/'. $service_provider->id . '/edit') }}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i>
                EDIT</a>
            <a href="#" onclick="window.print();return false;" class="btn btn-primary btn-sm"><i
                    class="fa fa-print"></i> PRINT</a>
        </div>
    </div>
    <hr class="my-3 my-md-4">
    <div class="row">
        <div class="col-3 col-md-2">
            <div class="border border-1 rounded bg-">
                @if($service_provider->logo == null)
                    <img class="img-fluid" src="{{ asset('assets/img/service_provider_placeholder.jpeg') }}" width="250" height="500">
                @else
                    <img class="img-fluid" src="{{ asset('storage/' . $service_provider->logo) }}" width="250" height="400">
                @endif
            </div>
        </div>
        <div class="col-9 col-md-5">
            <h3 class="text-uppercase h4 p-0 m-0"><b>BIO DATA</b></h3>
            <hr class="my-1 my-md-3">

            @include('components.detail-item', [
                't' => 'name',
                's' => $service_provider->name
            ])

            @if($service_provider->registration_number)
                @include('components.detail-item', [
                    't' => 'registration number',
                    's' => $service_provider->registration_number,
                ])
            @endif

            @if($service_provider->date_of_registration)
                @include('components.detail-item', [
                    't' => 'date of registration',
                    's' => $service_provider->date_of_registration
                ])
            @endif

            @include('components.detail-item', [
                't' => 'physical address',
                's' => $service_provider->physical_address,
            ])
            @if($service_provider->postal_address)
                @include('components.detail-item', [
                    't' => 'postal address',
                    's' => $service_provider->postal_address,
                ])
            @endif

            @if($service_provider->email)
                @include('components.detail-item', [
                    't' => 'email',
                    's' => $service_provider->email,
                ])
            @endif

            @if($service_provider->telephone)
                @include('components.detail-item', [
                    't' => 'Telephone',
                    's' => $service_provider->telephone,
                ])
            @endif


            @include('components.detail-item', [
            't' => 'verified',
            's' => $service_provider->is_verified ? 'Yes' : 'No',
            ])

        </div>



    </div>

    {{-- <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
    <h3 class="text-uppercase h4 p-0 m-0 text-center"><b>Contact Persons</b></h3>
    <hr class="m-0 pt-0 mb-3">
    <table class="table table-bordered table-striped table-hover">
        <tr class="text-bold">
            <td>Name</td>
            <td>Position</td>
            <td>Email</td>
            <td>Phone Number(s)</td>
        </tr>

        @foreach ($service_provider->contact_persons as $person)
            <tr>
                <td>{{ $person->name }}</td>
                <td>{{ $person->position }}</td>
                <td>{{ $person->email }}</td>
                <td>{{ $person->phone1. "  ".$person->phone2 }}</td>
            </tr>
        @endforeach

    </table>
    
    <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
    <h3 class="text-uppercase h4 p-0 m-0 text-center"><b>Profile</b></h3>
    <hr class="m-0 pt-0 mb-3">

    <p class="text-justify">{!! $service_provider->brief_profile !!}</p> --}}

    @if($service_provider->mission != 'NULL')
        <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
        <h3 class="text-uppercase h4 p-0 m-0 text-center"><b>Mission</b></h3>
        <hr class="m-0 pt-0 mb-3">
        <p class="text-justify">{!! $service_provider->mission !!}</p>
    @endif

    <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
    <h3 class="text-uppercase h4 p-0 m-0 text-center"><b>Services Offered</b></h3>
    <hr class="m-0 pt-0 mb-3">
    <p class="text-justify">{!! $service_provider->services_offered !!}</p>

    <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
    <h3 class="text-uppercase h4 p-0 m-0 text-center"><b>Target & Disabilities</b></h3>
    <hr class="m-0 pt-0 mb-3">
    <p class="text-justify">{{ $service_provider->target_group}}</p>
    <p class="text-justify">{{ $service_provider->disability_category}}</p>


    <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
    <h3 class="text-uppercase h4 p-0 m-0 text-center"><b>Regions Of Operation</b></h3>
    <hr class="m-0 pt-0 mb-3">
    <p class="text-justify">{{ $service_provider->level_of_operation }}</p>

    <p class="text-justify">{{ $service_provider->districts_of_operation }}</p>

    @if($service_provider->affiliated_organizations != 'NULL')
        <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
        <h3 class="text-uppercase h4 p-0 m-0 text-center"><b>Affilitated Organisations</b></h3>
        <hr class="m-0 pt-0 mb-3">
        <p class="text-justify">{{ $service_provider->affiliated_organizations }}</p>
    @endif

</div>
<style>
    .content-header {
        display: none;
    }
</style>
