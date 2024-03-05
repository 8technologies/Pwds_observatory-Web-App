<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded=[];


    public function serviceProvider()
    {
        return $this->belongsTo(ServiceProvider::class);
    }

    public function districts()
    {
        return $this->belongsToMany(District::class);
    }

}
