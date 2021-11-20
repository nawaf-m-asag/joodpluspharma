<?php

namespace Botble\Medical\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class LaboratoriesRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'lab_name'=> 'required',
            'phone'=> 'required',
            'email'=> 'required',
            'address'=> 'required',
            'status'=> 'required',
           
        ];
    }
}
