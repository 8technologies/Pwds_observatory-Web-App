<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\ExcelExporter;

class OrganisationsExcelExporter extends ExcelExporter
{
    protected $fileName = 'Organisations.xlsx';

    protected $columns = [
        'name' => 'Name',
        'registration_number' => 'Registration number',
        'date_of_registration' => 'Date of registration',
        'membership_type' => 'Membership type',
        'physical_address' => 'Physical address'
    ];
    
}