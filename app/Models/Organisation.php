<?php

namespace App\Models;

use App\Admin\Extensions\Column\OpenMap;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organisation extends Model
{
    use HasFactory;

    public function setAttachmentsAttribute($value)
    {
        $this->attributes['attachments'] = json_encode($value);
    }

    public function getAttachmentsAttribute($value)
    {
        return json_decode($value);
    }

    public function districtsOfOperation()
    {
        return $this->belongsToMany(District::class)->withTimestamps();
    }

    public function districtOfOperation()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    /**
     * Programs or initiatives run by this organisation
     */
    public function programs()
    {
        return $this->hasMany(Program::class);
    }

    public function leaderships()
    {
        return $this->hasMany(Leadership::class);
    }

    public function parentOrganisation()
    {
        return $this->hasOne(Organisation::class, 'parent_organisation_id')->where('id', $this->id);
    }

    public function opds()
    {
        return $this->hasMany(Organisation::class, 'parent_organisation_id')->where('relationship_type','opd');
    }

    public function district_unions()
    {
        return $this->hasMany(Organisation::class, 'parent_organisation_id')->where('relationship_type','du');
    }

    public function contact_persons()
    {
        return $this->hasMany(OrganisationContactPerson::class);
    }

    public function memberships()
    {
        return $this->hasMany(Organisation::class);
    }

    public function administrator() {
        return $this->belongsTo(User::class, 'user_id');
    }

}
