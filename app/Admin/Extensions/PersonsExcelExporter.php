<?php

namespace App\Admin\Extensions;

use App\Models\District;
use App\Models\Person;
use Encore\Admin\Grid\Exporters\ExcelExporter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;

class PersonsExcelExporter extends ExcelExporter implements WithMapping, FromCollection
{
    protected $fileName = 'Persons.xlsx';

    protected $headings = [
        'created_at' => 'Created At',
        'name' => 'Surname',
        'other_names' => 'Other Names',
        'id_number' => 'ID Number',
        'sex' => 'Gender',
        'dob' => 'Date Of Birth',
        'district_id' => 'District of Residence',
        'profiler' => 'Profiler',
        'disabilities' => 'Disability Category', // Added column for disabilities
    ];

    protected $districtMapping;

    public function __construct()
    {
        // Fetch district mapping from District model
        $this->districtMapping = District::pluck('name', 'id')->toArray();
    }

    public function collection()
    {
        // Fetch persons with their disabilities
        return Person::WhereHas('disabilities')->with('disabilities')->get();
    }

    public function map($person): array
    {
        return [
            $person->created_at->format('Y-m-d'), // Format created_at to return only the date
            $person->name,
            $person->other_names,
            $person->id_number,
            $person->sex,
            $person->dob,
            $this->getDistrictName($person->district_id),
            $person->profiler,
            $this->getDisabilityNames($person), // Include the disability names
        ];
    }

    protected function getDistrictName($districtId)
    {
        return $this->districtMapping[$districtId] ?? 'Unknown';
    }

    protected function getDisabilityNames($person)
    {
        // Get the disability names as a comma-separated string
        return $person->disabilities->pluck('name')->implode(', ');
    }
}
