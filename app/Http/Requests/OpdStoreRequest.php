<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpdStoreRequest extends FormRequest
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
            'name' => 'required',
            'vision' => 'required',
            'mission' => 'required',
            'core_values' => 'required',
            'brief_profile' => 'required',
            'districtsOfOperation' => 'required|array',
            'districtsOfOperation.*' => 'required|exists:districts,id',
        ];
    }
}
