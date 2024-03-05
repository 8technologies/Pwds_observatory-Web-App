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
            <a href="{{ admin_url('people/' . $pwd->id . '/edit') }}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i>
                EDIT</a>
            <a href="#" onclick="window.print();return false;" class="btn btn-primary btn-sm"><i
                    class="fa fa-print"></i> PRINT</a>
        </div>
    </div>
    <hr class="my-3 my-md-4">
    <div class="row">
        <div class="col-3 col-md-2">
            <div class="border border-1 rounded bg-">
                @if($pwd->photo == null)
                    <img class="img-fluid" src="{{ asset('assets/img/user-1.png') }}" width="250" height="500">
                @else
                <img class="img-fluid" src="{{ asset('storage/' . $pwd->photo) }}" width="250" height="500">
                @endif
            </div>
        </div>
        <div class="col-9 col-md-5">
            <h3 class="text-uppercase h4 p-0 m-0"><b>BIO DATA</b></h3>
            <hr class="my-1 my-md-3">

            @include('components.detail-item', [
                't' => 'name',
                's' => $pwd->name . ' ' . $pwd->other_names,
            ])

            @include('components.detail-item', ['t' => 'sex', 's' => $pwd->sex])
            @include('components.detail-item', [
                't' => 'Date of birth',
                's' => $pwd->dob,
            ])
            @include('components.detail-item', [
                't' => 'Phone number',
                's' => $pwd->phone_number . ' ' . $pwd->phone_number_2,
            ])
            @include('components.detail-item', [
                't' => 'Id Number',
                's' => $pwd->id_number,
            ])

            @include('components.detail-item', [
                't' => 'Ethnicity',
                's' => $pwd->ethnicity,
            ])

            @include('components.detail-item', [
                't' => 'marital status',
                's' => $pwd->marital_status,
            ])
            @include('components.detail-item', [
                't' => 'district of origin',
                's' => $pwd->districtOfOrigin->name ?? '',
            ])

        </div>

        <div class="col-9 col-md-4">
            <h3 class="text-uppercase h4 p-0 m-0"><b> NEXT OF KIN</b></h3>
            <hr class="my-1 my-md-3">

            @include('components.detail-item', [
                't' => 'names',
                's' => $pwd->next_of_kin_last_names . ' ' . $pwd->next_of_kin_other_names,
            ])
            @include('components.detail-item', [
                't' => 'id number',
                's' => $pwd->next_of_kin_id_number,
            ])
            @include('components.detail-item', [
                't' => 'gender',
                's' => $pwd->next_of_kin_gender,
            ])
            @include('components.detail-item', [
                't' => 'relationship',
                's' => $pwd->next_of_kin_relationship,
            ])

            @if ($pwd->next_of_kin_email == null)
                @include('components.detail-item', [
                    't' => 'email',
                    's' => $pwd->next_of_kin_email,
                ])
            @endif
            @include('components.detail-item', [
                't' => 'phone number',
                's' => $pwd->next_of_kin_phone_number . ' ' . $pwd->next_of_kin_alternative_phone_number,
            ])
            @include('components.detail-item', [
                't' => 'address',
                's' => $pwd->next_of_kin_address,
            ])



        </div>

    </div>

    <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
    <h3 class="text-uppercase h4 p-0 m-0 text-center"><b>Disabilities</b></h3>
    <hr class="m-0 pt-0">

    <ul>
        @foreach($pwd->disabilities as $disability)
            <li>{{ $disability->name }}</li>
        @endforeach
    </ul>

    <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
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

    @if($pwd->is_employed == 1)
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
            <td>{{  $pwd->employer }}</td>
            <td>{{ $pwd->position }}</td>
            <td>{{ $pwd->year_of_employment }}</td>
        </tr>

    </table>

    @endif

    @if($pwd->is_formerly_employed)

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

    @endif


</div>
<style>
    .content-header {
        display: none;
    }
</style>
