<div class="container bg-white p-1 p-md-5">
    <div class="d-md-flex justify-content-between">
        <div>
            {{-- <h2 class="m-0 p-0 text-dark h3 text-uppercase"><b>Suspect {{ ' - ' . $organisation->uwa_suspect_number  '-' }}</b> --}}
            </h2>
        </div>
        <div class="mt-3 mt-md-0 float-left">
            @if($organisation->membership_type == 'member') 
                <a class="btn btn-sm btn-primary mx-3" href="{{url('admin/opds/create') }}">Add OPD</a>
                <a class="btn btn-sm btn-info mx-3" href="{{url('admin/district-unions/create') }}">Add District Union</a>
            @elseif($organisation->membership_type == 'both') 
                <a class="btn btn-sm btn-info mx-3" href="{{url('admin/people/create') }}">Add Person With Disability</a>
                <a class="btn btn-sm btn-primary mx-3" href="{{url('admin/opds/create') }}">Add OPD</a>
                <a class="btn btn-sm btn-info mx-3" href="{{url('admin/district-unions/create') }}">Add District Union</a>
            @else
                <a class="btn btn-sm btn-info mx-3" href="{{url('admin/people/create') }}">Add Person With Disability</a>
            @endif
        </div>
        <div class="mt-3 mt-md-0">
            @isset($_SERVER['HTTP_REFERER'])
                <a href="{{ $_SERVER['HTTP_REFERER'] }}" class="btn btn-secondary btn-sm"><i class="fa fa-chevron-left"></i>
                    BACK
                    TO ALL LIST</a>
            @endisset
            <a href="{{ admin_url(request()->segment(2) .'/'. $organisation->id . '/edit') }}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i>
                EDIT</a>
            <a href="#" onclick="window.print();return false;" class="btn btn-primary btn-sm"><i
                    class="fa fa-print"></i> PRINT</a>
        </div>
    </div>
    <hr class="my-3 my-md-4">
    <div class="row">
        <div class="col-3 col-md-2">
            <div class="border border-1 rounded bg-">
                <img class="img-fluid" src="{{ asset('storage/' . $organisation->logo) }}" width="250" height="400">
            </div>
        </div>
        <div class="col-9 col-md-5">
            <h3 class="text-uppercase h4 p-0 m-0"><b>BIO DATA</b></h3>
            <hr class="my-1 my-md-3">

            @include('components.detail-item', [
                't' => 'name',
                's' => $organisation->name
            ])

            @include('components.detail-item', [
                't' => 'registration number',
                's' => $organisation->registration_number,
            ])
            @include('components.detail-item', [
                't' => 'date of registration',
                's' => $organisation->date_of_registration
            ])

            
            @include('components.detail-item', [
                't' => 'Type of Organisation',
                's' => $organisation->relationship_type == 'opd' ? 'OPD' : 'District Union',
            ])

            @include('components.detail-item', [
                't' => 'Membership Type',
                's' => $organisation->membership_type,
            ])

            @include('components.detail-item', [
                't' => 'physical address',
                's' => $organisation->physical_address,
            ])

        </div>



    </div>

    <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
    <h3 class="text-uppercase h4 p-0 m-0 text-center"><b>Contact Persons</b></h3>
    <hr class="m-0 pt-0 mb-3">
    <table class="table table-bordered table-striped table-hover">
        <tr class="text-bold">
            <td>Name</td>
            <td>Position</td>
            <td>Email</td>
            <td>Phone Number(s)</td>
        </tr>

        @foreach ($organisation->contact_persons as $person)
            <tr>
                <td>{{ $person->name }}</td>
                <td>{{ $person->position }}</td>
                <td>{{ $person->email }}</td>
                <td>{{ $person->phone1. "  ".$person->phone2 }}</td>
            </tr>
        @endforeach

    </table>

    <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
    <h3 class="text-uppercase h4 p-0 m-0 text-center"><b>Mission</b></h3>
    <hr class="m-0 pt-0 mb-3">

    <p class="text-justify">{!! $organisation->mission !!}</p>

    <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
    <h3 class="text-uppercase h4 p-0 m-0 text-center"><b>Vision</b></h3>
    <hr class="m-0 pt-0 mb-3">

    <p class="text-justify">{!! $organisation->vision !!}</p>

    <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
    <h3 class="text-uppercase h4 p-0 m-0 text-center"><b>Core Values</b></h3>
    <hr class="m-0 pt-0 mb-3">

    <p class="text-justify">{!! $organisation->core_values !!}</p>

    <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
    <h3 class="text-uppercase h4 p-0 m-0 text-center"><b>Profile</b></h3>
    <hr class="m-0 pt-0 mb-3">

    <p class="text-justify">{!! $organisation->brief_profile !!}</p>






</div>
<style>
    .content-header {
        display: none;
    }
</style>
