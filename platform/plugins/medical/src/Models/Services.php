<?php

namespace Botble\Medical\Models;
use Botble\Base\Models\BaseModel;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Traits\EnumCastable;
class Services extends BaseModel
{

    use EnumCastable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'med_services';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'status',
    ];

    public $timestamps = true;
     /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
