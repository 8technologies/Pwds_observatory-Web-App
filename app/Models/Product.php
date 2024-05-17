<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $fillable = [
        'name',
        'type',
        'photo',
        'details',
        'price',
        'service_provider_id'
    ];


    public function serviceProvider()
    {
        return $this->belongsTo(ServiceProvider::class);
    }

    public function districts()
    {
        return $this->belongsToMany(District::class);
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->details = strip_tags($product->details);
        });

        static::updating(function ($product) {
            $product->details = strip_tags($product->details);
        });


        static::deleting(function ($product) {
            $product->districts()->detach();
        });
    }
}
