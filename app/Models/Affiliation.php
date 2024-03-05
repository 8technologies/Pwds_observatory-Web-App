<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affiliation extends Model
{
    use HasFactory;

    protected $guarded = [];


    protected $table = "organisation_person";

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
