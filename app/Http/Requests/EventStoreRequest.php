<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
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
    }
}
