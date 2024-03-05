<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Innovation extends Model
{
    use HasFactory;

    public function getInnovatorsAttribute($value)
    {
        return array_values(json_decode($value, true) ?: []);
    }

    public function setInnovatorsAttribute($value)
    {
        $this->attributes['innovators'] = json_encode(array_values($value));
    }

    public function disabilities()
    {
        return $this->belongsToMany(Disability::class);
    }
}
