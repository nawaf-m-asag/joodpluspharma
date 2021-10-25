<?php

namespace Botble\App_setting\Models;

use Botble\Base\Models\BaseModel;

class App_setting extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'app_settings';

    /**
     * @var array
     */
    protected $fillable = [
        'variable',
        'value'
    ];

    public $timestamps = false;
   
}
