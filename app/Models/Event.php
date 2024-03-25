<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'theme',
        'photo',
        'details',
        'prev_event_title',
        'number_of_attendants',
        'number_of_speakers',
        'number_of_experts',
        'venue_name',
        'venue_photo',
        'venue_map_photo',
        'event_date',
        'address',
        'gps_latitude',
        'gps_longitude',
        'video'
    ];

    public function tickets()
    {
        return $this->hasMany(EventTicket::class);
    }

    public function bookings()
    {
        return $this->hasMany(EventBooking::class);
    }

    public function speakers()
    {
        return $this->hasMany(EventSpeaker::class);
    }
}
