<?php

namespace App\Http\Controllers;

use App\Exports\TemplateExport;

class TemplateExportController extends Controller
{
    public function downloadTemplate()
    {
        $export = new TemplateExport();
        return $export->downloadTemplate();
    }
}

