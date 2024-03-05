<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProviderContactPerson extends Model
{
    use HasFactory;

    protected $table = 'service_provider_contact_persons';

    protected $guarded = [];

    public function service_provider()
    {
        return $this->belongsTo(ServiceProvider::class);
    }
}
