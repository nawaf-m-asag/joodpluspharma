<?php

namespace Botble\Notification\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;

class Notification extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'image',
        'message',
        'date_sent',
        'type',
        'type_id'
    ];

    public $timestamps = false;
   
}
