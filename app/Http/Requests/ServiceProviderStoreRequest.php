<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceProviderStoreRequest extends FormRequest
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
            'name' => 'required',
            'telephone' => 'required',
            'email' => 'required',
            'physical_address' => 'required',
            'services_offered' => 'required',
            'disability_categories' => 'required|array',
            'disability_categories.*' => 'required|exists:disabilities,id',
            'districts_of_operations' => 'required|array',
            'districts_of_operations.*' => 'required|exists:districts,id',
        ];
    }
}
