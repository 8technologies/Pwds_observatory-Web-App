<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disability extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function people()
    {
        return $this->belongsToMany(Person::class);
    }

    public function innovations()
    {
        return $this->belongsToMany(Innovation::class);
    }

    public function serviceProviders()
    {
        return $this->belongsToMany(ServiceProvider::class)->withTimestamps();
    }

    public function counselling_centres()
    {
        return $this->belongsToMany(CounsellingCentre::class)->withTimestamps();
    }
}
