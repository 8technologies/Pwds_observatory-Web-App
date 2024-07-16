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
        'dob' => 'Date Of Birth ',
        'district_id' => 'District of Residence',
        'profiler' => 'Profiler',
        'categories' => 'Disability Category'
    ];
}
