<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\PEOPLE\ImportPeople;
use App\Imports\PersonImport;
use App\Models\DataImport;
use App\Models\District;
use App\Models\Person;
use App\Models\User;
use App\Models\Utils;
use Illuminate\Http\Request;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Layout\Content;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class ImportPeopleController extends AdminController
{
    public function import_people_process(Request $request)
    {
        $id = $request->id;
        $dataImport = DataImport::find($id);
        if ($dataImport == null) {
            throw new \Exception('Data Import not found');
        }
        $file = public_path('storage/' . $dataImport->file);

        // Check if file exists
        if (!file_exists($file)) {
            throw new \Exception('File not found');
        }

        $data = null;
        try {
            // Read excel file without importing
            $data = Excel::toArray([], $file);
        } catch (\Exception $e) {
            throw new \Exception('Import failed: ' . $e->getMessage());
        }

        $headers = $data[0][0];
        $isFirstRow = true;
        $total_records = 0;
        $total_imported = 0;
        $total_failed = 0;
        $u = User::find($dataImport->user_id);
        $error_message = '';
        $results = []; // Store results for display
        


        foreach ($data[0] as $row) {
            // Skip first row
            if ($isFirstRow) {
                $isFirstRow = false;
                continue;
            }

            $total_records++;
            $name = $row[0];
            // Check if name is not empty
            if (empty($name)) {
                $total_failed++;
                $error_message .= "Name for record $total_records is empty. <br>";
                continue;
            }

            $phone = null;
            if ($row[5] != null && strlen($row[5]) > 5) {
                $phone = Utils::prepare_phone_number($row[5]);
                if (!Utils::phone_number_is_valid($phone)) {
                    $phone = $row[5];
                }
            }

            if ($phone != null && strlen($phone) > 3) {
                $existing = Person::where('phone_number', $phone)->first();
                if ($existing != null) {
                    $total_failed++;
                    $error_message .= "Phone number $phone for record $total_records already exists. <br>";
                    continue;
                }
            }

            $person = new Person();
            $person->name = $name;
            $person->other_names = $row[1];
            $person->age = $row[4];
            $person->address = $row[3];
            $person->phone_number = $phone;
            $person->email = $row[9];
            $person->marital_status = $row[10];
            $person->is_approved = 1;
            $person->organisation_id = $u->organisation_id;
            $person->ethnicity = $row[6];
            if ($person->age != null) {
                $person->dob = date('Y-m-d', strtotime('-' . $person->age . ' years'));
            }
            $person->sub_county = $row[7];
            $person->village = $row[8];
            $person->sex = $row[2];
            $person->profiler = $row[11];
            $person->religion = $row[12];
            $person->is_formal_education = $row[13];
            $person->informal_education = $row[14];
            $person->education_level = $row[15];
            $person->disability = $row[3];
            $person->district_of_origin = $dataImport->district;
            $person->district_id = $dataImport->district;

            try {
                $person->save();
                // Handle disabilities
                if (!empty($row[3])) {
                    $disabilities = explode(',', $row[3]);
                    foreach ($disabilities as $disabilityName) {
                        $disabilityName = trim($disabilityName);
                        $disability = \App\Models\Disability::firstOrCreate(['name' => $disabilityName]);
                        $person->disabilities()->attach($disability);
                    }
                }
                $total_imported++;
                $status = "SUCCESS";
            } catch (\Exception $e) {
                $total_failed++;
                $error_message .= "Failed to save record $total_records. " . $e->getMessage() . "<br>";
                $status = "FAILED";
            }

            // Collect results for display
            $results[] = [
                'record' => $total_records,
                'status' => $status,
                'data' => array_combine($headers, $row) // Map headers to row values
            ];
        }

        $dataImport->total_records = $total_records;
        $dataImport->total_imported = $total_imported;
        $dataImport->total_failed = $total_failed;
        $dataImport->error_message = $error_message;
        $dataImport->processed = 'Yes';
        $dataImport->save();

        return view('admin.import_results', [
            'results' => $results,
            'total_records' => $total_records,
            'total_imported' => $total_imported,
            'total_failed' => $total_failed,
            'error_message' => $error_message
        ]);
    }
}
