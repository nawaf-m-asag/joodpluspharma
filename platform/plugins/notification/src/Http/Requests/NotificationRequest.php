<?php

namespace Botble\Notification\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class NotificationRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'=> 'required',
            'image'=>'',
            'type'=>'required',
            'message'=>'required',
            'date_sent'=>'required'
        ];
    }
}
