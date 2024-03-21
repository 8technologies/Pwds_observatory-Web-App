<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PeopleStoreRequest extends FormRequest
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
        //validate Education level, should be one of the following For example, 'Formal Education', 'Informal Education', else 'No Education'
        if ($this->input('education_level') !== 'Formal Education' || $this->input('education_level') !== 'Informal Education' || $this->input('education_level') !== 'No Education') {
            return [
                'education_level' => 'required|in:Formal Education,Informal Education,No Education',
            ];
        }
        //validate Formal Education, It should be one of the following For example, 'Primary', 'Secondary - UCE',  'Secondary - UACE', 'PHD', 'Bachelor's Degree', 'Master's Degree', 
        // 'Diploma', 'Certificate', 'Vocational Training', 'None'
        if ($this->input('education_level') === 'Formal Education') {
            return [
                'education_level' => 'required|in:Formal Education,Informal Education,No Education',
                'formal_education' => 'required|in:Primary,Secondary - UCE, Secondary - UACE,PHD,Bachelor\'s Degree,Master\'s Degree, Diploma, Certificate, Vocational Training',
            ];
        }
        return [
            'name' => 'required',
            'other_names' => 'required',
            'age' => 'required|int:min:0',
            'address' => 'required',
            'phone_number' => 'required|phone:UG',
            'phone_number_2' => 'nullable|phone:UG',
            'disabilities' => 'required|array',
            'disabilities.*' => 'required|exists:disabilities,id',
            'dob' => 'required|date_format:d-m-Y',
            'district_of_origin' => 'required',
            'district_of_residence' => 'required',
            'village' => 'required',
            'sub_county' => 'required',
            //validate Education level
            'education_level' => 'required|in:Formal Education,Informal Education,No Education',
            //validate Formal Education
            'is_formal_education' => 'required|in:Primary,Secondary - UCE, Secondary - UACE,PHD,Bachelor\'s Degree,Master\'s Degree, Diploma, Certificate, Vocational Training',
        ];
    }
}
