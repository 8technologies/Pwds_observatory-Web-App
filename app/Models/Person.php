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
        'ethnicity',
        'marital_status',
        'religion',
        'place_of_birth',
        'birth_hospital',
        'birth_no_hospital_description',
        'languages',
        'employer',
        'position',
        'district_id',
        'opd_id',
        'aspirations',
        'skills',
        'is_formal_education',
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
        'profiler'
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

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function districtOfOrigin()
    {
        return $this->belongsTo(District::class, 'district_of_origin');
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



            if ($person->is_employed == 0) {
                $person->employment_status = 'Unemployed';
            }

            $user = auth()->user();
            if($user != null){   
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
                    if($d != null){
                    $person->district_id = $organisation->district_id;
                }
                $person->is_approved = 1;
                }
            }

            $person->district_of_residence = $person->district_id;
            $person->categories_pricessed = 'No';
        });

        static::saving(function ($person) {
            // $person->addPerson();

            $person->name = ucfirst(strtolower($person->name));
            $person->other_names = ucfirst(strtolower($person->other_names));
            $person->sub_county = ucfirst(strtolower($person->sub_county));
            $person->village = ucfirst(strtolower($person->village));
            $person->profiler = ucfirst(strtolower($person->profiler));

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

            if ($person->is_employed == 0) {
                $person->employment_status = 'Unemployed';
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
