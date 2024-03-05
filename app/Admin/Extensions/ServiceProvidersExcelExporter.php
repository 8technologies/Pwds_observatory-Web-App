<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\ExcelExporter;

class ServiceProvidersExcelExporter extends ExcelExporter
{
    protected $fileName = 'ServiceProviders.xlsx';

    protected $columns = [
        'name' => 'Name',
        'registration_number' => 'Registration number',
        'date_of_registration' => 'Date of registration',
        'physical_address' => 'Physical address',
        'email' => 'Email',
        'telephone' => 'Telephone',
        'is_verified' => 'Verified',
        'services_offered' => 'Services offered',
        'level_of_operation' => 'Level of operation',
        'districts_of_operation' => 'Districts of operation',
        'target_group' => 'Target group',
        'disability_category' => 'Disability category',
        'mission' => 'Mission',
        'affiliated_organizations' => 'Affiliated organization'




    ];
    
}