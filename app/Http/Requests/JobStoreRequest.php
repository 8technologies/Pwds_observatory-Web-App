<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobStoreRequest extends FormRequest
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
            //Required fields
            'title' => 'required',
            'location' => 'required',
            'description' => 'required',
            'type' => 'required',
            'minimum_academic_qualification' => 'required',
            'required_experience' => 'required',
            'deadline' => 'required',
        ];
    }
}
