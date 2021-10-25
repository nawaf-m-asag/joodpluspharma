<?php

namespace Botble\App_setting\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;

class Time extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'time_slots';

    /**
     * @var array
     */
    protected $fillable = [
        'title' ,
        'from_time',
        'to_time',
        'last_order_time',
        'status'
    ];

    public $timestamps = false;
   
}
