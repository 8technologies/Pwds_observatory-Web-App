<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected  $table = 'districts';


    /**
     *  Has many organisations operations
     */
    public function organisations()
    {
        return $this->belongsToMany(Organisation::class)->withTimestamps();
    }

    /**
     *  Has many people
     */
    public function people()
    {
        return $this->hasMany(Person::class, 'district_of_origin');
    }

    /**
     * Has many service- providers
     */
    public function service_providers()
    {
        return $this->belongsToMany(ServiceProvider::class)->withTimestamps();
    }

    /**
     * Has many products
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function districtUnion()
    {
        return $this->hasOne(Organisation::class, 'district_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function counselling_centres()
    {
        return $this->belongsToMany(CounsellingCentre::class)->withTimestamps();
    }
}
