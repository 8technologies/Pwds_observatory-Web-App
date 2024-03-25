<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsPostStoreRequest extends FormRequest
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
            'title' => 'required',
            'details' => 'required',
            'post_category_id' => 'required',
            'views' => 'required',
            'description' => 'required',
            'photo' => 'required'
        ];
        /*
        
Full texts
id	
created_at	
updated_at	
title	
details	
user_id	
post_category_id	
views	
description	
photo
        */
    }
}
