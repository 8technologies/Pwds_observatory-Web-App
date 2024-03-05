<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;

    protected $table = 'organisation_organisation';

    public function parentOrganisation()
    {
        return $this->belongsTo(Organisation::class, 'parent_organisation_id');
    }

    public function childOrganisation()
    {
        return $this->belongsTo(Organisation::class, 'child_organisation_id');
    }

}
