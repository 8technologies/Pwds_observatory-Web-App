<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganisationContactPerson extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }
}
