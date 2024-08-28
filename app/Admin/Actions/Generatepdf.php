<?php

namespace App\Admin\Actions;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Barryvdh\DomPDF\Facade\Pdf;

class GeneratePdf extends RowAction
{
    public $name = 'Generate PDF';

    public function handle(Model $model)
    {
        // Load the data from the report
        $data = [
            'title' => $model->title,
            'description' => $model->description,
            'report_data' => $model->report_data,
        ];

        // Generate the PDF
        $pdf = Pdf::loadView('admin.reports.report_template', $data);

        // Save the PDF to the file path or stream it
        $filePath = "storage/reports/report_{$model->id}.pdf";
        $pdf->save(public_path($filePath));

        // Update the file_path field in the database
        $model->file_path = $filePath;
        $model->status = 'generated';
        $model->save();

        return $this->response()->success('PDF generated successfully.')->refresh();
    }

    public function dialog()
    {
        $this->confirm('Are you sure you want to generate this PDF?');
    }
}
