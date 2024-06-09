<?php

namespace App\Admin\Extensions;

use App\Models\District;
use Encore\Admin\Grid\Exporters\ExcelExporter;
use Maatwebsite\Excel\Concerns\WithMapping;

class PersonsExcelExporter extends ExcelExporter implements WithMapping
{
    protected $fileName = 'Persons.xlsx';

    protected $columns = [
        'created_at' => 'Created At',
        'name' => 'Surname',
        'other_names' => 'Other Names',
        'id_number' => 'ID Number',
        'sex' => 'Gender',
        'dob' => 'Date Of Birth',
        'district_id' => 'District of Residence',
        'profiler' => 'Profiler',
    ];

    protected $districtMapping;

    public function __construct()
    {
        // Fetch district mapping from District model
        $this->districtMapping = District::pluck('name', 'id')->toArray();
    }

    public function map($person): array
    {
        return [
            $person->created_at->format('Y-m-d'), // Format created_at to return only the date
            $person->name,
            $person->other_names,
            $person->id_number,
            $person->sex,
            // $this->getDisabilityNames($person),
            $person->dob,
            $this->getDistrictName($person->district_id),
            $person->profiler,
        ];
    }

    protected function getDistrictName($districtId)
    {
        return $this->districtMapping[$districtId] ?? 'Unknown';
    }
}

// namespace App\Admin\Extensions;

// use App\Models\District;
// use App\Models\Person;
// use Encore\Admin\Grid\Exporters\ExcelExporter;
// use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\WithMapping;

// class PersonsExcelExporter extends ExcelExporter implements WithMapping, FromCollection
// {
//     protected $fileName = 'Persons.xlsx';

//     protected $columns = [
//         'created_at' => 'Created At',
//         'name' => 'Surname',
//         'other_names' => 'Other Names',
//         'id_number' => 'ID Number',
//         'sex' => 'Gender',
//         'disabilities' => 'Disability Category',
//         'dob' => 'Date',
//         'district_id' => 'District of Origin',
//         'profiler' => 'Profiler',
//     ];

//     protected $districtMapping;
//     protected $persons;

//     public function __construct()
//     {
//         // Fetch district mapping from District model
//         $this->districtMapping = District::pluck('name', 'id')->toArray();

//         // Eager load the disabilities relationship and ensure only unique persons are fetched
//         $this->persons = Person::with('disabilities')->latest()->get()->unique('id');
//     }

//     public function collection()
//     {
//         return $this->persons;
//     }

//     public function map($person): array
//     {
//         return [
//             $person->created_at->format('Y-m-d'), // Format created_at to return only the date
//             $person->name,
//             $person->other_names,
//             $person->id_number,
//             $person->sex,
//             $this->getDisabilityNames($person),
//             $person->dob,
//             $this->getDistrictName($person->district_id),
//             $person->profiler,
//         ];
//     }

//     protected function getDistrictName($districtId)
//     {
//         return $this->districtMapping[$districtId] ?? 'Unknown';
//     }

//     protected function getDisabilityNames($person)
//     {
//         // Ensure disabilities are loaded
//         if (!$person->relationLoaded('disabilities')) {
//             $person->load('disabilities');
//         }

//         // Check if the person has disabilities
//         if ($person->disabilities->isNotEmpty()) {
//             // Sort disabilities by the latest date
//             $disabilities = $person->disabilities->sortByDesc('created_at');

//             // Filter out duplicate disabilities (if any) and retrieve names
//             $uniqueDisabilities = $disabilities->unique('name')->pluck('name')->toArray();

//             // Join disability names with a comma separator
//             return implode(', ', $uniqueDisabilities);
//         } else {
//             return ''; // Return empty string if there are no disabilities
//         }
//     }
// }
