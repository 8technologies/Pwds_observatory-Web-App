<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leadership extends Model
{
    use HasFactory;

    protected $casts = [
        'members' => 'json'
    ];

    public function getMembersAttribute($value)
    {
        return array_values(json_decode($value, true) ?: []);
    }

    public function setMembersAttribute($value)
    {
        $this->attributes['members'] = json_encode(array_values($value));
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

}
