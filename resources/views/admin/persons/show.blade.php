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
            <a href="{{ admin_url('people/' . $pwd->id . '/edit') }}" class="btn btn-warning btn-sm"><i
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
                @if ($pwd->photo == null)
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
        <h3 class="text-uppercase h4 p-0 m-0"><b>NEXT OF KIN</b></h3>
        <hr class="my-1 my-md-3">

        @forelse($pwd->next_of_kins as $kin)
            @include('components.detail-item', [
            't' => 'Names',
            's' => $kin->next_of_kin_last_name . ' ' . $kin->next_of_kin_other_names,
            ])

            @include('components.detail-item', [
            't' => 'ID Number',
            's' => $kin->next_of_kin_id_number,
            ])

            @include('components.detail-item', [
            't' => 'Gender',
            's' => $kin->next_of_kin_gender,
            ])

            @include('components.detail-item', [
            't' => 'Relationship',
            's' => $kin->next_of_kin_relationship,
            ])

            @if($kin->next_of_kin_email)
            @include('components.detail-item', [
                't' => 'Email',
                's' => $kin->next_of_kin_email,
            ])
            @endif

            @include('components.detail-item', [
            't' => 'Phone Number',
            's' => $kin->next_of_kin_phone_number
                . ($kin->next_of_kin_alternative_phone_number
                    ? ' / '.$kin->next_of_kin_alternative_phone_number
                    : ''),
            ])

            @include('components.detail-item', [
            't' => 'Address',
            's' => $kin->next_of_kin_address,
            ])

            <hr>
        @empty
            <p class="text-muted"><em>No next of kin recorded.</em></p>
        @endforelse
        </div>


    </div>

    <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
    <h3 class="text-uppercase h4 p-0 m-0 text-center"><b>Disabilities</b></h3>
    <hr class="m-0 pt-0">

    <ul>
        @foreach ($pwd->disabilities as $disability)
            <li>{{ $disability->name }}</li>
        @endforeach
    </ul>
    @if ($pwd->academic_qualifications)
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
    @endif

    @if ($pwd->education_level == 'formal Education')
        <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
        <h3 class="text-uppercase h4 p-0 m-0 text-center"><b>Education</b></h3>
        <hr class="m-0 pt-0">
        <table class="table table-bordered table-striped table-hover">
            <tr class="text-bold">
                <td>Level of Education</td>
                <td>Field of Study</td>
                <td>Class</td>
            </tr>
            <tr>
                <td>{{ $pwd->is_formal_education }}</td>
                <td>{{ $pwd->field_of_study }}</td>
                <td>{{ $pwd->indicate_class }}</td>
            </tr>

        </table>
    @endif

    @if ($pwd->education_level == 'informal Education')
        <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
        <h3 class="text-uppercase h4 p-0 m-0 text-center"><b>Education</b></h3>
        <hr class="m-0 pt-0">
        <table class="table table-bordered table-striped table-hover">
            <p>Technical knowledge: {{ $pwd->informal_education }}</p>

        </table>
    @endif

    @if ($pwd->is_employed == 1)
        <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
        <h3 class="text-uppercase h4 p-0 m-0 text-center"><b>Current Employment</b></h3>
        <hr class="m-0 pt-0">

        @if ($pwd->employment_status == 'fomal employment')
            <table class="table table-bordered table-striped table-hover">
                <tr class="text-bold">
                    <td>Name</td>
                    <td>Position</td>
                </tr>
                <tr>
                    <td>{{ $pwd->employer }}</td>
                    <td>{{ $pwd->position }}</td>
                </tr>

            </table>
        @endif

        @if ($pwd->employment_status == 'self employment')
            <p>Occupation: {{ $pwd->occupation }}</p>
        @endif
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

    @endif


</div>
<style>
    .content-header {
        display: none;
    }
</style>
