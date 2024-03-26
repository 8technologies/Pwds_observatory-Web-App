<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProvider extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'registration_number',
        'date_of_registration',
        'user_id',
        'brief_profile',
        'physical_address',
        'attachments',
        'logo',
        'license',
        'certificate_of_registration',
        'is_verified',
        'email',
        'telephone',
        'services_offered',
        'districts_of_operation',
        'level_of_operation',
        'mission',
        'postal_address',
        'disability_category',
        'target_group',
        'affiliated_organizations',
    ];

    public function setAttachmentsAttribute($value)
    {
        $this->attributes['attachments'] = json_encode($value);
    }

    public function getAttachmentsAttribute($value)
    {
        return json_decode($value);
    }

    public function districts_of_operations()
    {
        return $this->belongsToMany(District::class)->withTimestamps();
    }

    public function contact_persons()
    {
        return $this->hasMany(ServiceProviderContactPerson::class);
    }

    // public function products()
    // {
    // }

    public function disability_categories()
    {
        return $this->belongsToMany(Disability::class)->withTimestamps();
    }
    public function administrator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
