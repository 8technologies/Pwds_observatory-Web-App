<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CounsellingCentreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'administrator_id' => 'required',
            'name' => 'required',
            'about' => 'required',
            // 'address' => 'nullable',
            // 'parish' => 'nullable',
            // 'village' => 'nullable',
            'phone_number' => 'required',
            'email' => 'nullable|email',
            // 'subcounty_id' => 'nullable|exists:subcounties,id',
            // 'website' => 'nullable|url',
            // 'photo' => 'nullable|image',
            // 'gps_latitude' => 'nullable|numeric',
            // 'gps_longitude' => 'nullable|numeric',
            // 'status' => 'nullable',
            // 'fees_range' => 'nullable',
            'districts' => 'required|array',
            'districts.*' => 'required|exists:districts,id',
            'disabilities' => 'required|array',
            'disabilities.*' => 'required|exists:disabilities,id',
        ];
    }
}
