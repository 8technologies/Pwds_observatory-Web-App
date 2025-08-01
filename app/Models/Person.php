<?php

namespace App\Models;

use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Facades\Admin;
use Error;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Person extends Model
{
    use HasFactory;

    protected $guarded = [
        'is_formal_education',
        'is_employed',
        'is_member',
        'is_same_address',
    ];



    protected $fillable = [
        'user_id',
        'local_id',
        'name',
        'other_names',
        'age',
        'address',
        'phone_number',
        'email',
        'phone_number_2',
        'dob',
        'sex',
        'photo',
        'district_of_origin',
        'district_of_residence',
        'id_number',
        'id_type',
        'ethnicity',
        'marital_status',
        'religion',
        'place_of_birth',
        'birth_hospital',
        'birth_no_hospital_description',
        'languages',
        'employer',
        'position',
        'year_of_employment',
        'district_id',
        'opd_id',
        'select_opd_or_du',
        'aspirations',
        'skills',
        'is_formal_education',
        'field_of_study',
        'indicate_class',
        'occupation',
        'informal_education',
        'is_employed',
        'is_member',
        'disability',
        'education_level',
        'sub_county',
        'village',
        'employment_status',
        'select_opd_or_du',
        'profiler',
        'is_verified',
        'is_approved',
        'next_of_kin_name',
        'next_of_kin_phone',
        'next_of_kin_relationship',
        'next_of_kin_email',
        'next_of_kin_address',
    ];

    protected $casts = [
        'age' => 'integer',

    ];

    public function association()
    {
        return $this->belongsTo(Association::class);
    }

    public function disabilities()
    {
        return $this->belongsToMany(Disability::class);
    }

    //belongs to district
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function districtOfOrigin()
    {
        return $this->belongsTo(District::class, 'district_of_origin');
    }
    /* 
    //getter for district_of_origin
    public function getDistrictOfOriginAttribute()
    {
        $d = District::find($this->district_of_origin);
        if ($d == null) {
            return 'Not mentioned.';
        }
        return $d->name;
    } */

    //getter for district_id 
    public function getDistrictIdAttribute($district_id)
    {
        if ($district_id == null || $district_id == 0 || $district_id == '') {
            $originDistrict = District::find($this->district_of_origin);
            if ($originDistrict != null) {
                $sql = "update people set district_id = ? where id = ?";
                DB::update($sql, [$originDistrict->id, $this->id]);
                $district_id = $originDistrict->id;
                return $district_id;
            }
        }
        return $district_id;
    }

    public function districtOfResidence()
    {
        return $this->belongsTo(District::class, 'district_of_residence');
    }

    public function academic_qualifications()
    {
        return $this->hasMany(AcademicQualification::class);
    }

    public function employment_history()
    {
        return $this->hasMany(EmploymentHistory::class);
    }

    public function next_of_kins()
    {
        return $this->hasMany(NextOfKin::class);
    }

    public function getDisabilityTextAttribute()
    {
        $d = Disability::find($this->disability_id);
        if ($d == null) {
            return 'Not mentioned.';
        }
        return $d->name;
    }

    protected static function boot()
    {
        parent::boot();

        //created

        static::created(function ($person) {
            try {
                $person->process_cats();
            } catch (\Throwable $th) {
            }
        });

        static::updated(function ($person) {
            try {
                $person->process_cats();
            } catch (\Throwable $th) {
            }
        });

        static::creating(function ($person) {
            //Checking for duplicates in Person

            $person->name = ucfirst(strtolower($person->name));
            $person->other_names = ucfirst(strtolower($person->other_names));
            $person->sub_county = ucfirst(strtolower($person->sub_county));
            $person->village = ucfirst(strtolower($person->village));
            $person->profiler = ucfirst(strtolower($person->profiler));


            //check if district_of_origin is not numetic, search for distrct withs same name
            if ($person->district_of_origin && !is_numeric($person->district_of_origin)) {
                $district = District::where('name', 'like', '%' . $person->district_of_origin . '%')->first();
                if ($district) {
                    $person->district_of_origin = $district->id;
                } else {
                    $person->district_of_origin = null; // or handle as needed
                }
            }

            //opd_id the same
            if (!is_numeric($person->opd_id)) {
                $user = auth()->user();
                if ($user != null) {
                    $organisation = Organisation::find($user->organisation_id);
                    if ($organisation != null) {
                        $person->opd_id = $organisation->id;
                    }
                }
            } else {
                $organisation = Organisation::find($person->opd_id);
                if ($organisation != null) {
                    $person->opd_id = $organisation->id;
                }
            }
            if (!is_numeric($person->opd_id)) {
                $person->opd_id = 1;
            }
            if ($person->is_member == 1 || $person->is_member == '1' || $person->is_member == 'Yes') {
                $person->is_member = 1;
            } else {
                $person->is_member = 0; // Set to 0 if not a member
            }

            if ($person->opd_id == 1 || $person->opd_id == '1' || $person->opd_id == 'Yes') {
                $person->opd_id = 1;
            } else {
                $person->opd_id = 0; // Set to 0 if not a member
            }

            if ($person->is_employed == 0) {
                $person->employment_status = 'Unemployed';
            }

            $user = auth()->user();
            if ($user != null) {
                $organisation = Organisation::find($user->organisation_id);

                //Ogiki Moses Odera 
                if ($organisation == null) {
                    // To Handle the case where no organization is found
                    return redirect()->back()->withErrors(['error' => 'No organization associated with this user.']);
                }


                if ($organisation->relationship_type == 'opd') {
                    $person->opd_id = $organisation->id;
                    $person->is_approved = 1;
                }
                if ($organisation->relationship_type == 'du') {
                    $d = District::find($organisation->district_id);
                    //Ogiki Moses Odera Changed from == to != to allow for the district to be set
                    if ($d != null) {
                        $person->district_id = $organisation->district_id;
                    }
                    $person->is_approved = 1;
                }
            }

            $person->district_of_residence = $person->district_id;
            $person->categories_pricessed = 'No';

            if ($person->district_id == null || $person->district_id == 0 || $person->district_id == '') {
                if ($person->district_of_origin) {
                    $district = District::find($person->district_of_origin);
                    if ($district != null) {
                        $person->district_id = $person->district_of_origin;
                    }
                }
            }
            if ($person->district_of_origin == null || $person->district_of_origin == 0 || $person->district_of_origin == '') {
                $district = District::find($person->district_id);
                if ($district != null) {
                    $person->district_of_origin = $person->district_id;
                }
            }
        });

        static::saving(function ($person) {
            // $person->addPerson();

            $person->name = ucfirst(strtolower($person->name));
            $person->other_names = ucfirst(strtolower($person->other_names));
            $person->sub_county = ucfirst(strtolower($person->sub_county));
            $person->village = ucfirst(strtolower($person->village));
            $person->profiler = ucfirst(strtolower($person->profiler));

            // Update 'm' to 'Male' and 'f' to 'Female' in sex attribute
            if (strtolower($person->sex) === 'm') {
                $person->sex = 'Male';
            } elseif (strtolower($person->sex) === 'f') {
                $person->sex = 'Female';
            }

            //is_employed == 0 must be taken as unemployed
            if ($person->is_employed == 2) {
                $person->employment_status = 'unemployed';
            }

            // $user = auth()->user();
            // $organisation = Organisation::find($user->organisation_id);
            // if (!$organisation) {
            //     die('Wait for admin approval');
            // } else {
            //     $person->is_approved = 1;
            // }
        });

        static::updating(function ($person) {
            $person->name = ucfirst(strtolower($person->name));
            $person->other_names = ucfirst(strtolower($person->other_names));
            $person->sub_county = ucfirst(strtolower($person->sub_county));
            $person->village = ucfirst(strtolower($person->village));
            $person->profiler = ucfirst(strtolower($person->profiler));
            $person->is_approved = 1;
            $person->categories_pricessed = 'No';

            //check if district_of_origin is not numetic, search for distrct withs same name
            if ($person->district_of_origin && !is_numeric($person->district_of_origin)) {
                $district = District::where('name', 'like', '%' . $person->district_of_origin . '%')->first();
                if ($district) {
                    $person->district_of_origin = $district->id;
                } else {
                    $person->district_of_origin = null; // or handle as needed
                }
            }
            //opd_id if not numeric, search for opd withs same name
            if (!is_numeric($person->opd_id)) {
                $organisation = Organisation::where('name', 'like', '%' . $person->opd_id . '%')->first();
                if ($organisation) {
                    $person->opd_id = $organisation->id;
                } else {
                    $person->opd_id = null; // or handle as needed
                }
            }



            if ($person->is_employed == 0) {
                $person->employment_status = 'Unemployed';
            }

            if ($person->district_id == null || $person->district_id == 0 || $person->district_id == '') {
                if ($person->district_of_origin) {
                    $district = District::find($person->district_of_origin);
                    if ($district != null) {
                        $person->district_id = $person->district_of_origin;
                    }
                }
            }
            if ($person->district_of_origin == null || $person->district_of_origin == 0 || $person->district_of_origin == '') {
                $district = District::find($person->district_id);
                if ($district != null) {
                    $person->district_of_origin = $person->district_id;
                }
            }
            if (!is_numeric($person->opd_id)) {
                $person->opd_id = 1;
            }
            // If is_member is 1 or 'Yes', set opd_id to 1,

            if ($person->is_member == 1 || $person->is_member == '1' || $person->is_member == 'Yes') {
                $person->is_member = 1;
            } else {
                $person->is_member = 0; // Set to 0 if not a member
            }

            if ($person->opd_id == 1 || $person->opd_id == '1' || $person->opd_id == 'Yes') {
                $person->opd_id = 1;
            } else {
                $person->opd_id = 0; // Set to 0 if not a member
            }
            // $user = Admin::user();
            // $organisation = Organisation::find($user->organisation_id);
            // if (!$organisation) {
            //     die('Wait for admin approval');
            // } else {
            //     $person->is_approved = 1;
            // }
        });
    }

    public static function updateRecord()
    {
        $people_records = Person::select('id', 'name', 'other_names')->get();
        foreach ($people_records as $record) {
            $record->name = ucfirst(strtolower($record->name));
            $record->other_names = ucfirst(strtolower($record->other_names));
            $record->save();
        }
    }

    public function addPerson(Request $request)
    {
        // Check for duplicates
        $person_age = $request->input('age');
        $person_name = $request->input('name');
        $person_other_name = $request->input('other_name');
        $person_full_name = $person_name . " " . $person_other_name;

        // Check for duplicates
        $duplicate = self::where('name', $person_full_name)
            ->where('age', $person_age)
            ->first();

        if ($duplicate) {
            return redirect()->route('people', ['person' => $duplicate->id]) // Use the existing person's ID
                ->with('success', $person_name . ' already exists in the database');
        }

        // Allow the creation of the new person
        return true;
    }

    public function process_cats()
    {
        $cats = $this->disabilities;
        $ifFirst = false;
        $cats_text = '';

        foreach ($cats as $key => $d) {
            if (!$ifFirst) {
                $cats_text = $d->name;
                $ifFirst = true;
            } else {
                $cats_text = $cats_text . ', ' . $d->name;
            }
        }
        if (strlen($cats_text) < 2) {
            //update sql, set processed yes
            //$sql = "update people set categories_pricessed = 'Yes' where id = " . $this->id;
            //DB::update($sql);
            //make the above prepared statement
            $sql = "update people set categories_pricessed = 'Yes' where id = ?";
            DB::update($sql, [$this->id]);
            return;
        }

        //sql escape
        $cats_text = str_replace("'", "''", $cats_text);

        //update sql
        //$sql = "update people set categories = '" . $cats_text . "', categories_pricessed = 'Yes' where id = " . $this->id;
        //DB::update($sql);
        //make the above prepared statement
        $sql = "update people set categories
        = ?, categories_pricessed = 'Yes' where id = ?";
        DB::update($sql, [$cats_text, $this->id]);
    }
}
