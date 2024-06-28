<?php

namespace App\Admin\Actions\PEOPLE;

use App\Imports\PersonImport;
use Encore\Admin\Actions\Action;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportPeople extends Action
{
    public $name = "import people";
    protected $selector = '.import-people';


    public function handle(Request $request)
    {
        // The following code gets the uploaded file, then uses the package `maatwebsite/excel` to process and upload your file and save it to the database.
        $file = $request->file('file');

        // Import the data
        try {
            Excel::import(new PersonImport, $file);
            return $this->response()->success('Import complete!')->refresh();
        } catch (\Exception $e) {
            return $this->response()->error('Import failed: ' . $e->getMessage());
        }
    }

    public function form()
    {
        $this->file('file', 'Please select file');
    }

    public function html()
    {
        return <<<HTML
        <a class="btn btn-sm btn-default import-people">Import People</a>
HTML;
    }
}
