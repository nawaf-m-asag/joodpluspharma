<?php

namespace Botble\Medical\Http\Requests;

use Botble\Support\Http\Requests\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class PrescriptionRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'       => 'required|max:255',
            'email'      => 'email|nullable|max:60',
            'phone'      => 'required|numeric',
            'city_id'       => 'required|max:120',
            'address'    => 'required|max:120',
            'notes'=>'nullable|string',
            'image_file'=>'required_without:file',
            'file'=>'required_without:image_file'
        ];

   
        return $rules;
    }
}
