<?php

namespace Botble\Medical\Models;
use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Botble\Ecommerce\Enums\OrderStatusEnum;
use Botble\Ecommerce\Models\Customer;
use Botble\Medical\Models\Doctors;
use Botble\Medical\Models\Specialties;
class Consulting extends BaseModel
{

    use EnumCastable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'med_consulting';

    /**
     * @var array
     */

    protected $fillable = [
            'con_type',
            'specialty_id',
            'doctor_id',
            'p_name',
            'p_age',
            'p_sex',
            'female_status',
            'chronic_diseases',
            'operations',
            'medicines',
            'desc_situation',
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
    public function doctor()
    {
        return $this->belongsTo(Doctors::class, 'doctor_id', 'id')->withDefault();
    }
    public function specialty()
    {
        return $this->belongsTo(Specialties::class, 'specialty_id', 'id')->withDefault();
    }
    
    protected $casts = [
        'status'  => OrderStatusEnum::class,
    ];
   
}
