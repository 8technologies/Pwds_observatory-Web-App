<?php

namespace App\Models;

use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Facades\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        'employment_status'
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

        static::creating(function ($person) {
            $person->name = ucfirst(strtolower($person->name));
            $person->other_names = ucfirst(strtolower($person->other_names));
            $person->sub_county = ucfirst(strtolower($person->sub_county));
            $person->village = ucfirst(strtolower($person->village));

            if ($person->is_employed == 0) {
                $person->employment_status = 'Unemployed';
            }


            $person->district_of_residence = $person->district_id;
        });

        static::saving(function ($person) {
            $person->name = ucfirst(strtolower($person->name));
            $person->other_names = ucfirst(strtolower($person->other_names));
            $person->sub_county = ucfirst(strtolower($person->sub_county));
            $person->village = ucfirst(strtolower($person->village));

            //is_employed == 0 must be taken as unemployed
            if ($person->is_employed == 0) {
                $person->employment_status = 'Unemployed';
            }
        });

        static::updating(function ($person) {
            $person->name = ucfirst(strtolower($person->name));
            $person->other_names = ucfirst(strtolower($person->other_names));
            $person->sub_county = ucfirst(strtolower($person->sub_county));
            $person->village = ucfirst(strtolower($person->village));

            if ($person->is_employed == 0) {
                $person->employment_status = 'Unemployed';
            }
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
}
