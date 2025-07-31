<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\PeopleStoreRequest;
use App\Models\Person as Person;
use App\Http\Controllers\Controller;
use App\Models\Api_Utils;
use App\Models\Organisation;
use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Imports\ModelManager;

class PersonController extends Controller
{
    //function for returning all people
    public function index(Request $request)
    {
        $user = $request->user(); // Assuming the authenticated user is retrieved from the request
        if ($user == null) {
            return Api_Utils::error("User not found", 404);
        }

        try {
            $organisation = Organisation::find($user->organisation_id);
            if (!$organisation) {
                return Api_Utils::error("Organisation not found", 404);
            }

            $query = Person::query();

            if ($user->inRoles(['nudipu', 'administrator'])) {
                $query->orderBy('created_at', 'desc');
            } elseif ($user->isRole('district-union')) {
                $query->where('district_id', $organisation->district_id)->orderBy('created_at', 'desc');
            } elseif ($user->isRole('opd')) {
                $query->where('opd_id', $organisation->id)->orderBy('created_at', 'desc');
            } else {
                return Api_Utils::error("User role is not authorized", 403);
            }

            $people = $query->paginate($request->per_page);

            if ($people->isEmpty()) {
                return Api_Utils::error("No data retrieved from the database.", 404);
            }

            return Api_Utils::success($people, "People successfully returned", 200);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 500); // Changed to 500 to indicate server error
        }
    }


    //function for creating a new person
    public function store(PeopleStoreRequest $request)
    {
        //Creating person and storing them to the databas

        try {
            $person = new Person();
            $person = Person::create($request->all());
            $person->disabilities()->attach($request->input('disabilities'));
            return Api_Utils::success($person, "Person created", 200);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 400);
        }
    }

    //function for retrieving data for a specific person
    public function show($id)
    {
        //retrieve a person from the database
        try {
            $person = Person::FindorFail($id);
            return Api_Utils::success($person, "Person returned", 200);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 400);
        }
    }

    //function for updating a resord    
    public function update(PeopleStoreRequest $request, $id)
    {

        //updating a person
        try {
            $person = Person::findOrFail($id);
            $person->update($request->all());
            $person->disabilities()->sync($request->input('disabilities'));
            return Api_Utils::success($person, "Person updated successfully", 200);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 400);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //delete a person
        try {
            $person = Person::FindorFail($id);
            $person->delete();
            return Api_Utils::success($person, "Person deleted", 200);
        } catch (\Exception $e) {
            return Api_Utils::error($e->getMessage(), 400);
        }
    }

    /**
     * Store or update a person profile (for mobile app v2)
     * Handles both create and update based on local_id, phone_number, or id_number
     */
    public function storeOrUpdate(Request $request)
    {
        try {
            $data = $request->all();

            // Remove empty/null values to avoid overwriting existing data
            $data = array_filter($data, function ($value) {
                return !is_null($value) && $value !== '' && $value !== 'null';
            });

            // Remove _text fields that don't exist in database
            $textFields = ['district_text', 'opd_text', 'disabilities_text'];
            foreach ($textFields as $textField) {
                if (isset($data[$textField])) {
                    unset($data[$textField]);
                }
            }

            // Remove sync-related fields that don't belong in database
            $syncFields = ['sync_status', 'sync_error', 'last_sync_attempt', 'is_local_only', 'local_upload_status', 'local_upload_message'];
            foreach ($syncFields as $syncField) {
                if (isset($data[$syncField])) {
                    unset($data[$syncField]);
                }
            }

            // Remove other non-database fields
            $invalidFields = ['categories_pricessed', 'created_at', 'updated_at', 'id']; // Laravel handles timestamps automatically
            foreach ($invalidFields as $invalidField) {
                if (isset($data[$invalidField])) {
                    unset($data[$invalidField]);
                }
            }

            $existingPerson = null;

            // Check for existing record by local_id first
            if (!empty($data['local_id'])) {
                $existingPerson = Person::where('local_id', $data['local_id'])->first();
            }

            // If not found by local_id, check by phone number
            if (!$existingPerson && !empty($data['phone_number'])) {
                $existingPerson = Person::where('phone_number', $data['phone_number'])->first();
            }

            // If not found by phone, check by id_number and id_type combination
            if (!$existingPerson && !empty($data['id_number']) && !empty($data['id_type'])) {
                $existingPerson = Person::where('id_number', $data['id_number'])
                    ->where('id_type', $data['id_type'])
                    ->first();
            }

            // Handle photo upload
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $this->handlePhotoUpload($request->file('photo'));
                $data['photo'] = $photoPath;
            } elseif ($request->has('photo_base64') && !empty($request->photo_base64)) {
                $photoPath = $this->handleBase64Photo($request->photo_base64);
                $data['photo'] = $photoPath;
            }

            // Prepare disabilities data
            if (isset($data['disabilities']) && is_string($data['disabilities'])) {
                try {
                    $disabilitiesData = json_decode($data['disabilities'], true);
                    if (is_array($disabilitiesData)) {
                        $disabilityIds = array_column($disabilitiesData, 'id');
                        $disabilityIds = array_filter($disabilityIds, 'is_numeric');
                    } else {
                        $disabilityIds = [];
                    }
                } catch (\Exception $e) {
                    $disabilityIds = [];
                }
                unset($data['disabilities']); // Remove from mass assignment data
            }

            if ($existingPerson) {
                // Update existing person - set each field individually
                if (!empty($data['name'])) $existingPerson->name = $data['name'];
                if (!empty($data['other_names'])) $existingPerson->other_names = $data['other_names'];
                if (!empty($data['age'])) $existingPerson->age = $data['age'];
                if (!empty($data['address'])) $existingPerson->address = $data['address'];
                if (!empty($data['phone_number'])) $existingPerson->phone_number = $data['phone_number'];
                if (!empty($data['email'])) $existingPerson->email = $data['email'];
                if (!empty($data['phone_number_2'])) $existingPerson->phone_number_2 = $data['phone_number_2'];
                if (!empty($data['dob'])) $existingPerson->dob = $data['dob'];
                if (!empty($data['sex'])) $existingPerson->sex = $data['sex'];
                if (!empty($data['photo'])) $existingPerson->photo = $data['photo'];
                if (!empty($data['district_of_origin'])) $existingPerson->district_of_origin = $data['district_of_origin'];
                if (!empty($data['district_of_residence'])) $existingPerson->district_of_residence = $data['district_of_residence'];
                if (!empty($data['id_number'])) $existingPerson->id_number = $data['id_number'];
                if (!empty($data['id_type'])) $existingPerson->id_type = $data['id_type'];
                if (!empty($data['ethnicity'])) $existingPerson->ethnicity = $data['ethnicity'];
                if (!empty($data['marital_status'])) $existingPerson->marital_status = $data['marital_status'];
                if (!empty($data['religion'])) $existingPerson->religion = $data['religion'];
                if (!empty($data['place_of_birth'])) $existingPerson->place_of_birth = $data['place_of_birth'];
                if (!empty($data['birth_hospital'])) $existingPerson->birth_hospital = $data['birth_hospital'];
                if (!empty($data['birth_no_hospital_description'])) $existingPerson->birth_no_hospital_description = $data['birth_no_hospital_description'];
                if (!empty($data['languages'])) $existingPerson->languages = $data['languages'];
                if (!empty($data['employer'])) $existingPerson->employer = $data['employer'];
                if (!empty($data['position'])) $existingPerson->position = $data['position'];
                if (!empty($data['year_of_employment'])) $existingPerson->year_of_employment = $data['year_of_employment'];
                if (!empty($data['district_id'])) $existingPerson->district_id = $data['district_id'];
                if (!empty($data['opd_id'])) $existingPerson->opd_id = $data['opd_id'];
                if (!empty($data['aspirations'])) $existingPerson->aspirations = $data['aspirations'];
                if (!empty($data['skills'])) $existingPerson->skills = $data['skills'];
                if (!empty($data['is_formal_education'])) $existingPerson->is_formal_education = $data['is_formal_education'];
                if (!empty($data['field_of_study'])) $existingPerson->field_of_study = $data['field_of_study'];
                if (!empty($data['indicate_class'])) $existingPerson->indicate_class = $data['indicate_class'];
                if (!empty($data['occupation'])) $existingPerson->occupation = $data['occupation'];
                if (!empty($data['informal_education'])) $existingPerson->informal_education = $data['informal_education'];
                if (!empty($data['is_employed'])) $existingPerson->is_employed = $data['is_employed'];
                if (!empty($data['is_member'])) $existingPerson->is_member = $data['is_member'];
                if (!empty($data['disability'])) $existingPerson->disability = $data['disability'];
                if (!empty($data['education_level'])) $existingPerson->education_level = $data['education_level'];
                if (!empty($data['sub_county'])) $existingPerson->sub_county = $data['sub_county'];
                if (!empty($data['village'])) $existingPerson->village = $data['village'];
                if (!empty($data['employment_status'])) $existingPerson->employment_status = $data['employment_status'];
                if (!empty($data['select_opd_or_du'])) $existingPerson->select_opd_or_du = $data['select_opd_or_du'];
                if (!empty($data['profiler'])) $existingPerson->profiler = $data['profiler'];
                if (!empty($data['is_verified'])) $existingPerson->is_verified = $data['is_verified'];
                if (!empty($data['is_approved'])) $existingPerson->is_approved = $data['is_approved'];
                if (!empty($data['next_of_kin_name'])) $existingPerson->next_of_kin_name = $data['next_of_kin_name'];
                if (!empty($data['next_of_kin_phone'])) $existingPerson->next_of_kin_phone = $data['next_of_kin_phone'];
                if (!empty($data['next_of_kin_relationship'])) $existingPerson->next_of_kin_relationship = $data['next_of_kin_relationship'];
                if (!empty($data['next_of_kin_email'])) $existingPerson->next_of_kin_email = $data['next_of_kin_email'];
                if (!empty($data['next_of_kin_address'])) $existingPerson->next_of_kin_address = $data['next_of_kin_address'];
                if (!empty($data['local_id'])) $existingPerson->local_id = $data['local_id'];

                $existingPerson->save();

                // Sync disabilities
                if (isset($disabilityIds)) {
                    $existingPerson->disabilities()->sync($disabilityIds);
                }

                return Api_Utils::success(
                    $this->formatPersonResponse($existingPerson),
                    "Person profile updated successfully",
                    200
                );
            } else {
                // Create new person - set each field individually
                $person = new Person();
                
                if (!empty($data['name'])) $person->name = $data['name'];
                if (!empty($data['other_names'])) $person->other_names = $data['other_names'];
                if (!empty($data['age'])) $person->age = $data['age'];
                if (!empty($data['address'])) $person->address = $data['address'];
                if (!empty($data['phone_number'])) $person->phone_number = $data['phone_number'];
                if (!empty($data['email'])) $person->email = $data['email'];
                if (!empty($data['phone_number_2'])) $person->phone_number_2 = $data['phone_number_2'];
                if (!empty($data['dob'])) $person->dob = $data['dob'];
                if (!empty($data['sex'])) $person->sex = $data['sex'];
                if (!empty($data['photo'])) $person->photo = $data['photo'];
                if (!empty($data['district_of_origin'])) $person->district_of_origin = $data['district_of_origin'];
                if (!empty($data['district_of_residence'])) $person->district_of_residence = $data['district_of_residence'];
                if (!empty($data['id_number'])) $person->id_number = $data['id_number'];
                if (!empty($data['id_type'])) $person->id_type = $data['id_type'];
                if (!empty($data['ethnicity'])) $person->ethnicity = $data['ethnicity'];
                if (!empty($data['marital_status'])) $person->marital_status = $data['marital_status'];
                if (!empty($data['religion'])) $person->religion = $data['religion'];
                if (!empty($data['place_of_birth'])) $person->place_of_birth = $data['place_of_birth'];
                if (!empty($data['birth_hospital'])) $person->birth_hospital = $data['birth_hospital'];
                if (!empty($data['birth_no_hospital_description'])) $person->birth_no_hospital_description = $data['birth_no_hospital_description'];
                if (!empty($data['languages'])) $person->languages = $data['languages'];
                if (!empty($data['employer'])) $person->employer = $data['employer'];
                if (!empty($data['position'])) $person->position = $data['position'];
                if (!empty($data['year_of_employment'])) $person->year_of_employment = $data['year_of_employment'];
                if (!empty($data['district_id'])) $person->district_id = $data['district_id'];
                if (!empty($data['opd_id'])) $person->opd_id = $data['opd_id'];
                if (!empty($data['aspirations'])) $person->aspirations = $data['aspirations'];
                if (!empty($data['skills'])) $person->skills = $data['skills'];
                if (!empty($data['is_formal_education'])) $person->is_formal_education = $data['is_formal_education'];
                if (!empty($data['field_of_study'])) $person->field_of_study = $data['field_of_study'];
                if (!empty($data['indicate_class'])) $person->indicate_class = $data['indicate_class'];
                if (!empty($data['occupation'])) $person->occupation = $data['occupation'];
                if (!empty($data['informal_education'])) $person->informal_education = $data['informal_education'];
                if (!empty($data['is_employed'])) $person->is_employed = $data['is_employed'];
                if (!empty($data['is_member'])) $person->is_member = $data['is_member'];
                if (!empty($data['disability'])) $person->disability = $data['disability'];
                if (!empty($data['education_level'])) $person->education_level = $data['education_level'];
                if (!empty($data['sub_county'])) $person->sub_county = $data['sub_county'];
                if (!empty($data['village'])) $person->village = $data['village'];
                if (!empty($data['employment_status'])) $person->employment_status = $data['employment_status'];
                if (!empty($data['select_opd_or_du'])) $person->select_opd_or_du = $data['select_opd_or_du'];
                if (!empty($data['profiler'])) $person->profiler = $data['profiler'];
                if (!empty($data['is_verified'])) $person->is_verified = $data['is_verified'];
                if (!empty($data['is_approved'])) $person->is_approved = $data['is_approved'];
                if (!empty($data['next_of_kin_name'])) $person->next_of_kin_name = $data['next_of_kin_name'];
                if (!empty($data['next_of_kin_phone'])) $person->next_of_kin_phone = $data['next_of_kin_phone'];
                if (!empty($data['next_of_kin_relationship'])) $person->next_of_kin_relationship = $data['next_of_kin_relationship'];
                if (!empty($data['next_of_kin_email'])) $person->next_of_kin_email = $data['next_of_kin_email'];
                if (!empty($data['next_of_kin_address'])) $person->next_of_kin_address = $data['next_of_kin_address'];
                if (!empty($data['local_id'])) $person->local_id = $data['local_id'];

                $person->save();

                // Attach disabilities
                if (isset($disabilityIds)) {
                    $person->disabilities()->attach($disabilityIds);
                }

                return Api_Utils::success(
                    $this->formatPersonResponse($person),
                    "Person profile created successfully",
                    201
                );
            }
        } catch (\Exception $e) {
            Log::error('Person Store/Update Error: ' . $e->getMessage());
            Log::error('Request Data: ' . json_encode($request->all()));
            return Api_Utils::error("Failed to save person profile: " . $e->getMessage(), 500);
        }
    }

    /**
     * Handle photo file upload
     */
    private function handlePhotoUpload($photo): string
    {
        $fileName = 'person_' . Str::random(10) . '_' . time() . '.' . $photo->getClientOriginalExtension();
        return $photo->storeAs('person_photos', $fileName, 'public');
    }

    /**
     * Handle base64 photo upload
     */
    private function handleBase64Photo($base64Photo): string
    {
        // Remove data:image/...;base64, prefix if present
        if (strpos($base64Photo, 'data:image/') === 0) {
            $base64Photo = substr($base64Photo, strpos($base64Photo, ',') + 1);
        }

        $imageData = base64_decode($base64Photo);
        $fileName = 'person_' . Str::random(10) . '_' . time() . '.jpg';
        $filePath = 'person_photos/' . $fileName;

        Storage::disk('public')->put($filePath, $imageData);

        return $filePath;
    }

    /**
     * Format person response for API
     */
    private function formatPersonResponse($person)
    {
        $formattedPerson = $person->toArray();

        // Add disabilities information
        $disabilities = $person->disabilities;
        $formattedPerson['disabilities'] = $disabilities->map(function ($disability) {
            return [
                'id' => $disability->id,
                'name' => $disability->name,
            ];
        });
        $formattedPerson['disabilities_text'] = $disabilities->pluck('name')->implode(', ');

        // Format photo URL
        if ($person->photo) {
            $formattedPerson['photo_url'] = asset('storage/' . $person->photo);
        }

        return $formattedPerson;
    }
}
