<?php
use App\Models\Utils;
?><div class="container bg-white p-1 p-md-5">
    <div class="d-md-flex justify-content-between">
        <div>
            <h2 class="m-0 p-0 text-dark h3 text-uppercase"><b>Suspect {{ ' - ' . $s->uwa_suspect_number ?? '-' }}</b>
            </h2>
        </div>
        <div class="mt-3 mt-md-0">
            @isset($_SERVER['HTTP_REFERER'])
                <a href="{{ $_SERVER['HTTP_REFERER'] }}" class="btn btn-secondary btn-sm"><i class="fa fa-chevron-left"></i>
                    BACK
                    TO ALL LIST</a>
            @endisset
            <a href="{{ url('case-suspects/' . $s->id . '/edit') }}" class="btn btn-warning btn-sm"><i
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
                <img class="img-fluid" src="{{ $s->photo_url }}">
            </div>
        </div>
        <div class="col-9 col-md-5">
            <h3 class="text-uppercase h4 p-0 m-0"><b>BIO DATA</b></h3>
            <hr class="my-1 my-md-3">

            @include('components.detail-item', [
                't' => 'name',
                's' => $s->first_name . ' ' . $s->middle_name . ' ' . $s->last_name,
            ])
            @include('components.detail-item', ['t' => 'sex', 's' => $s->sex])
            @include('components.detail-item', [
                't' => 'Date of birth',
                's' => Utils::my_date($s->age),
            ])
            @include('components.detail-item', ['t' => 'Phone number', 's' => $s->phone_number])
            @include('components.detail-item', [
                't' => 'National id number',
                's' => $s->national_id_number,
            ])

            @include('components.detail-item', [
                't' => 'Country of origin',
                's' => $s->country,
            ])

            @include('components.detail-item', [
                't' => 'Ethnicity',
                's' => $s->ethnicity,
            ])

            {{--   @include('components.detail-item', [
                't' => 'District, Sub-county',
                's' => $s->sub_county->name_text,
            ]) --}}



            @include('components.detail-item', [
                't' => 'Parish,Village',
                's' => $s->parish . ', ' . $s->village,
            ])



            @include('components.detail-item', [
                't' => 'REPORTed on DATE',
                's' => 'sOME DATE',
            ])

            @include('components.detail-item', [
                't' => 'UWA SUSPECT',
                's' => 'some number',
            ])

            @include('components.detail-item', ['t' => 'occuptaion', 's' => $s->occuptaion])
        </div>
        <div class="pt-3 pt-md-0 col-md-5">
            <div class=" border border-primary p-3">
                <h3 class="text-uppercase h4 p-0 m-0 text-center"><b>Summary</b></h3>
                <hr class="border-primary mt-3">
                <div style="font-family: monospace; font-size: 16px;">
                    <p class="py-1 my-0 "><b>CASE DATE:</b>
                        {{ Utils::to_date_time($s->created_at) }}</p>
                    <p class="py-1 my-0 "><b>CASE TITLE:</b> <a href="{{ admin_url('cases/' . $s->id) }}"><span
                                class="text-primary"
                                title="View case details">{{ $s->title ?? $s->id }}</span></a>
                    </p>
                    <p class="py-1 my-0 "><b>CASE NUMBER:</b> {{ $s->case_number }}</p>


                    <p class="py-1 my-0"><b class="text-uppercase">CASE suspetcs:</b> {{ '12' }}
                    </p>
  

    


                </div>
            </div>
        </div>
    </div>

    <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
    <h3 class="text-uppercase h4 p-0 m-0 text-center"><b>Offences Committed</b></h3>
    <hr class="m-0 pt-0 mb-3">
    <ul>
        @foreach ([2,3,24,42] as $item)
            <li><b>{{ $item }}</b></li>
        @endforeach
    </ul>

    <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
    <h3 class="text-uppercase h4 p-0 m-0 text-center"><b>ARREST information</b></h3>
    <hr class="m-0 pt-0">

 


    <hr class="mt-4 mb-2 border-primary pb-0 mt-md-5 mb-md-5">
    <h3 class="text-uppercase h4 p-0 m-0 text-center"><b>Court information</b></h3>
    <hr class="m-0 pt-0">
 
 

    <hr class="my-5">
    <h3 class="text-uppercase h4 p-0 m-0 mb-2 text-center  mt-3 mt-md-5"><b>Other suspects involved in this case</b>
    </h3>
 

</div>
<style>
    .content-header {
        display: none;
    }
</style>
