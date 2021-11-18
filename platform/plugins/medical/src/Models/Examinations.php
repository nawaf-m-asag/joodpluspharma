<?php

namespace Botble\Medical\Models;
use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Botble\Ecommerce\Enums\OrderStatusEnum;
use Botble\Ecommerce\Models\Customer;
class Examinations extends BaseModel
{

    use EnumCastable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'med_examinations';

    /**
     * @var array
     */
   
    protected $fillable = [
            'p_name',
            'p_age',
            'p_sex',
            'd_name',
            'address',
            'lap_name',
            'required_checks',
            'user_id',
            'file',
            'created_at',
            'status'
    ];

    public $timestamps = true;
     /**
     * @var array
     */
  
    public function user()
    {
        return $this->belongsTo(Customer::class, 'user_id', 'id')->withDefault();
    }
  
    protected $casts = [
        'status'  => OrderStatusEnum::class,
    ];
   
}
