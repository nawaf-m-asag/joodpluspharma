<?php

namespace Botble\App_setting\Models;


use Botble\Base\Models\BaseModel;

class Area extends BaseModel
{
 
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'areas';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'city_id'
    ];
    public function City()
    {
        return $this->belongsTo('Botble\App_setting\Models\City');
    }

    public $timestamps = false;
   
}
