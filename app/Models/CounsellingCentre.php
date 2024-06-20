<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounsellingCentre extends Model
{
    use HasFactory;


    protected $fillable = [
        'administrator_id',
        'name',
        'about',
        'address',
        'parish',
        'village',
        'phone_number',
        'email',
        'subcouty_id',
        'website',
        'photo',
        'gps_latitude',
        'gps_longitude',
        'status',
        'fees_range'
    ];


    public static function boot()
    {
        parent::boot();
        self::deleting(function ($m) {
        });
        self::created(function ($m) {
        });
        self::creating(function ($m) {

            $m->district_id = 1;

            if ($m->subcounty_id != null) {
                $sub = Location::find($m->subcounty_id);
                if ($sub != null) {
                    $m->district_id = $sub->parent;
                }
            }
            return $m;
        });
        self::updating(function ($m) {

            $m->district_id = 1;
            if ($m->subcounty_id != null) {
                $sub = Location::find($m->subcounty_id);
                if ($sub != null) {
                    $m->district_id = $sub->parent;
                }
            }

            return $m;
        });
    }


    public function getSubcountyTextAttribute()
    {
        $d = Location::find($this->subcounty_id);
        if ($d == null) {
            return 'Not group.';
        }
        return $d->name_text;
    }
    protected $appends = ['subcounty_text'];

    public function districts()
    {
        return $this->belongsToMany(District::class)->withTimestamps();
    }

    public function disabilities()
    {
        return $this->belongsToMany(Disability::class)->withTimestamps();
    }
}
