<?php

namespace App\Admin\Extensions;

use App\Models\District;
use Encore\Admin\Grid\Exporters\ExcelExporter;
use Maatwebsite\Excel\Concerns\WithMapping;

class PersonsExcelExporter extends ExcelExporter implements WithMapping
{

    protected $fileName = 'Persons.xlsx';

    protected $columns = [
        'name' => 'Surname',
        'other_names' => 'Other Names',
        'id_number' => 'ID Number',
        'sex' => 'Gender',
        'dob' => 'Date ',
        'district_id' => 'District of Origin',
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
            $person->name,
            $person->other_names,
            $person->id_number,
            $person->sex,
            $person->dob,
            $this->getDistrictName($person->district_id), // Get district name here
            $person->profiler,
        ];
    }

    protected function getDistrictName($districtId)
    {
        return $this->districtMapping[$districtId] ?? 'Unknown';
    }
}
