<?php

namespace App\Admin\Extensions;

use App\Models\District;
use Encore\Admin\Grid\Exporters\ExcelExporter;
use Maatwebsite\Excel\Concerns\WithMapping;

class PersonsExcelExporter extends ExcelExporter implements WithMapping
{
    protected $fileName = 'Persons.xlsx';

    protected $districtMapping;

    public function __construct()
    {
        // Fetch district mapping from District model
        $this->districtMapping = District::pluck('name', 'id')->toArray();
    }

    protected $columns = [
        'name' => 'Surname',
        'other_names' => 'Other Names',
        'id_number' => 'ID Number',
        'phone_number' => 'Phone Number',
        'sex' => 'Gender',
        'is_formal_education'=>'Formal Education',
        'age' => 'Age',
        'dob' => 'Date Of Birth ',
        'district_id' => 'District of Residence',
        'profiler' => 'Profiler',
        'categories' => 'Disability Category'
        
    ];

    public function map($person): array
    {
        return [
            $person->name,
            $person->other_names,
            $person->id_number,
            $person->phone_number,
            $person->sex,
            $person->is_formal_education,
            $person->age,
            $person->dob,
            $this->getDistrictName($person->district_id),
            $person->profiler,
            $person->categories // Include the disability names
            

        ];
    }

    protected function getDistrictName($districtId)
    {
        return $this->districtMapping[$districtId] ?? 'Unknown';
    }
}
