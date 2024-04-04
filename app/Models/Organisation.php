<?php

namespace App\Models;

use App\Admin\Extensions\Column\OpenMap;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organisation extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_id',
        'name',
        'registration_number',
        'date_of_registration',
        'mission',
        'vision',
        'core_values',
        'brief_profile',
        'membership_type',
        'district_id',
        'physical_address',
        'website',
        'attachments',
        'logo',
        'certificate_of_registration',
        'constitution',
        'admin_email',
        'valid_from',
        'valid_to',
        'relationship_type',
    ];

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
        return $this->hasMany(Organisation::class, 'parent_organisation_id')->where('relationship_type', 'opd');
    }

    public function district_unions()
    {
        return $this->hasMany(Organisation::class, 'parent_organisation_id')->where('relationship_type', 'du');
    }

    public function contact_persons()
    {
        return $this->hasMany(OrganisationContactPerson::class);
    }

    public function memberships()
    {
        return $this->hasMany(Organisation::class);
    }

    public function administrator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public static function get_region($district_id)
    {
        $district = District::with('region')->find($district_id);
        if (!$district) {
            return null;
        }
        return $district->region->name;
    }

    public static function updateRegionIdForOldRecords()
    {
        // Find organisations with region_id set to zero
        $organisationsToUpdate = self::where('region_id', 0)->get();

        foreach ($organisationsToUpdate as $organisation) {
            $districtId = $organisation->district_id;

            // Retrieve the corresponding district and its region
            $district = District::with('region')->find($districtId);

            // If district and region are found, update the organisation's region_id
            if ($district && $district->region) {
                $organisation->update(['region_id' => $district->region->id]);
            }
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $district = District::find($model->district_id);
            if (!$district) {
                return 'District not found';
            }
            $model->region_id = $district->region_id;
        });
    }
}
