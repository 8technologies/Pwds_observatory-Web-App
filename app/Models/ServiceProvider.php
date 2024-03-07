<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProvider extends Model
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

    public function districts_of_operation()
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

    public function disability_category()
    {
        return $this->belongsToMany(Disability::class)->withTimestamps();
    }
    public function administrator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
