<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NextOfKin extends Model
{
    use HasFactory;

    //fillables
    protected $fillable = [
        'next_of_kin_last_name',
        'next_of_kin_other_names',
        'next_of_kin_id_number',
        'next_of_kin_gender',
        'next_of_kin_phone_number',
        'next_of_kin_email',
        'next_of_kin_relationship',
        'next_of_kin_address',
        'next_of_kin_alternative_phone_number',
        'person_id',
        'created_at',
        'updated_at',
    ]; 
    protected $guarded = [];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
