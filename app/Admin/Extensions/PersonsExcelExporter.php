<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\ExcelExporter;

class PersonsExcelExporter extends ExcelExporter
{
    protected $fileName = 'Persons.xlsx';

    protected $columns = [
        'name' => 'Surname',
        'other_names' => 'Other Names',
        'id_number' => 'ID Number',
        'sex' => 'Gender',
        'dob' => 'Date ',
        'district_of_origin' => 'District of Origin',
        'profiler' => 'Profiler',
    ];
}
