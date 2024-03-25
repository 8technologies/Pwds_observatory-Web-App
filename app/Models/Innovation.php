<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Innovation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'innovation_type',
        'photo',
        'innovators',
        'innovation_status',
        'description',
    ];

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
