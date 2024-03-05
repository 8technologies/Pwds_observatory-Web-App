<?php

namespace App\Models;

use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $guarded = [
        'is_formal_education',
        'is_employed',
        'is_member',
        'is_same_address'
    ];

    public function association(){
        return $this->belongsTo(Association::class);
    }

    public function disabilities(){
        return $this->belongsToMany(Disability::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class,'district_id');
    }

    public function districtOfOrigin()
    {
        return $this->belongsTo(District::class, 'district_of_origin');
    }

    public function academic_qualifications(){
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
        if($d == null){
            return 'Not mentioned.';
        }
        return $d->name;
    }

    

}
