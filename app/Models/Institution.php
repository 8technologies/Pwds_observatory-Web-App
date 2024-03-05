<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use HasFactory;
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


        
    public function getDisabilitiesAttribute($pictures)
    {
        if ($pictures != null)
            return json_decode($pictures, true);
    }


    public function setDisabilitiesAttribute($pictures)
    {
        if (is_array($pictures)) {
            $this->attributes['disabilities'] = json_encode($pictures);
        }
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
}
