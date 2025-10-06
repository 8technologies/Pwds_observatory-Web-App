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
    public function parseAge($value): ?int
    {
        if ($value === null) return null;
        if (is_int($value)) return ($value >= 0 && $value <= 120) ? $value : null;

        $value = trim((string)$value);
        if ($value === '') return null;

        // grab first integer in the string
        if (preg_match('/\d+/', $value, $m)) {
            $age = (int)$m[0];
            return ($age >= 0 && $age <= 120) ? $age : null;
        }
        return null;
    }


    public function import_people_process(Request $request)
    {
        $id = $request->id;
        $dataImport = DataImport::find($id);
        if (!$dataImport) {
            throw new \Exception('Data Import not found');
        }

        $file = public_path('storage/' . $dataImport->file);
        if (!file_exists($file)) {
            throw new \Exception('File not found');
        }

        try {
            // Read the whole workbook as arrays
            $sheets = Excel::toArray([], $file);
        } catch (\Exception $e) {
            throw new \Exception('Import failed: ' . $e->getMessage());
        }

        // We’ll use the first sheet
        $sheet = $sheets[0] ?? [];
        if (empty($sheet)) {
            throw new \Exception('Sheet is empty.');
        }

        // 1) Normalize headings to friendly keys (e.g., "Phone Number" => "phone_number")
        $rawHeaders = $sheet[0] ?? [];
        if (empty($rawHeaders)) {
            throw new \Exception('No header row found.');
        }
        $headers = array_map([$this, 'normalizeHeader'], $rawHeaders);

        $u = User::find($dataImport->user_id);

        $total_records = 0;
        $total_imported = 0;
        $total_failed = 0;
        $error_message = '';
        $results = [];

        // 2) Process each subsequent row as an associative array
        for ($i = 1; $i < count($sheet); $i++) {
            $row = $sheet[$i];
            $total_records++;

            // Guard: make row count match header count (pad/truncate)
            $row = array_pad($row, count($headers), null);
            $row = array_slice($row, 0, count($headers));

            // Associate: ['name' => 'John', 'phone_number' => '...']
            $rowAssoc = array_combine($headers, array_map('trim', $row));

            // Helper: fetch by any of these keys (lets you accept multiple header spellings)
            $get = function(array $candidates, $default = null) use ($rowAssoc) {
                foreach ($candidates as $key) {
                    if (array_key_exists($key, $rowAssoc) && strlen((string)$rowAssoc[$key]) > 0) {
                        return $rowAssoc[$key];
                    }
                }
                return $default;
            };

            $name = $get(['name', 'full_name', 'surname']);
            if (empty($name)) {
                $total_failed++;
                $error_message .= "Name for record {$total_records} is empty.<br>";
                $results[] = ['record' => $total_records, 'status' => 'FAILED', 'data' => $rowAssoc];
                continue;
            }

            // Read by header names
            $otherNames    = $get(['other_names', 'othernames', 'middle_names', 'middle_name']);
            $sex           = $get(['sex', 'gender']);
            $ageRaw        = $get(['age', 'years']);
            $age           = $this->parseAge($ageRaw); // returns ?int
            $disabilityStr = $get(['disability', 'disabilities']);
            $phoneRaw      = $get(['phone_number', 'phone', 'tel', 'mobile']);
            $ethnicity     = $get(['ethnicity', 'tribe']);
            $subCounty     = $get(['sub_county', 'subcounty']);
            $village       = $get(['village']);
            $email         = $get(['email', 'e_mail']);
            $marital       = $get(['marital_status', 'marital', 'marital status']);
            $profiler      = $get(['profiler name', 'profiler', 'recorder']);
            $religion      = $get(['religion']);
            $isFormalEdu   = $get(['is_formal_education', 'formal education', 'formal_edu']);
            $informalEdu   = $get(['informal education', 'informal_edu']);
            $educationLvl  = $get(['education_level', 'education', 'highest_education']);

            // Phone normalize/validate
            $phone = null;
            if (!empty($phoneRaw) && strlen($phoneRaw) > 5) {
                $prepared = \App\Models\Utils::prepare_phone_number($phoneRaw);
                $phone = \App\Models\Utils::phone_number_is_valid($prepared) ? $prepared : $phoneRaw;
            }

            // Uniqueness check by named columns
            $exists = Person::where('name', $name)
                ->where('other_names', $otherNames)
                ->where('sex', $sex)
                ->where('age', $age)
                ->where('disability', $disabilityStr)
                ->exists();

            if ($exists) {
                $total_failed++;
                $error_message .= "The person {$name} {$otherNames} is already registered.<br>";
                $results[] = ['record' => $total_records, 'status' => 'FAILED', 'data' => $rowAssoc];
                continue;
            }

            $person = new Person();
            $person->name                = $name;
            $person->other_names         = $otherNames;
            $person->age                 = $age;
            $person->address             = $disabilityStr; // (kept as your original mapping)
            $person->phone_number        = $phone;
            $person->email               = $email;
            $person->marital_status      = $marital;
            $person->is_approved         = 1;
            $person->organisation_id     = $u?->organisation_id;
            $person->ethnicity           = $ethnicity;
            $person->dob                 = $age ? date('Y-m-d', strtotime('-' . intval($age) . ' years')) : null;
            $person->sub_county          = $subCounty;
            $person->village             = $village;
            $person->sex                 = $sex;
            $person->profiler            = $profiler;
            $person->religion            = $religion;
            $person->is_formal_education = $isFormalEdu;
            $person->informal_education  = $informalEdu;
            $person->education_level     = $educationLvl;
            $person->disability          = $disabilityStr;
            $person->district_of_origin  = $dataImport->district;
            $person->district_id         = $dataImport->district;

            try {
                $person->save();
                Log::info("Imported person ID ");

                // Attach disabilities from comma-separated header value
                if (!empty($disabilityStr)) {
                    $disabilities = array_filter(array_map('trim', explode(',', $disabilityStr)));
                    foreach ($disabilities as $disabilityName) {
                        $disability = \App\Models\Disability::firstOrCreate(['name' => $disabilityName]);
                        $person->disabilities()->syncWithoutDetaching([$disability->id]);
                    }
                }

                $total_imported++;
                $results[] = ['record' => $total_records, 'status' => 'SUCCESS', 'data' => $rowAssoc];
            } catch (\Exception $e) {
                $total_failed++;
                $error_message .= "Failed to save record {$total_records}. {$e->getMessage()}<br>";
                $results[] = ['record' => $total_records, 'status' => 'FAILED', 'data' => $rowAssoc];
            }
        }

        $dataImport->total_records  = $total_records;
        $dataImport->total_imported = $total_imported;
        $dataImport->total_failed   = $total_failed;
        $dataImport->error_message  = $error_message;
        $dataImport->processed      = 'Yes';
        $dataImport->save();

        return view('admin.import_results', compact(
            'results', 'total_records', 'total_imported', 'total_failed', 'error_message'
        ));
    }

    /**
     * Turn a header like "Phone Number (Primary)" into "phone_number_primary".
     */
    private function normalizeHeader($h): string
    {
        $h = strtolower(trim($h));
        // Replace non-alphanumerics with underscores
        $h = preg_replace('/[^a-z0-9]+/i', '_', $h);
        // Collapse multiple underscores
        $h = preg_replace('/_+/', '_', $h);
        return trim($h, '_');
    }

    
}

