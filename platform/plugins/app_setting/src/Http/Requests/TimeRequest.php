<?php

namespace Botble\App_setting\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class TimeRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'   => 'required',
            'from_time'=>'required',
            'to_time'=>'required',
            'last_order_time'=>'required',
            'status'=>'required'
        ];
    }
}
