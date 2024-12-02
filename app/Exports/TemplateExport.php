<?php

namespace App\Exports;

use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;

class TemplateExport implements FromCollection
{
    public function collection()
    {
        // Since we are exporting a static file, this won't be used in this case
        return collect([]);
    }

    public function downloadTemplate()
{
    // Correct way to serve the file
    $filePath = public_path('templates/Pwd_Profiling_EightTech.xlsx');
    
    if (file_exists($filePath)) {
        return response()->file($filePath, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="Pwd_Profiling_EightTech.xlsx"',
        ]);
    }

    // Return an error message if the file doesn't exist
    return response()->json(['error' => 'File not found'], 404);
}

}
