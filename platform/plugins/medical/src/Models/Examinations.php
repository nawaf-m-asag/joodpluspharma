<?php

namespace Botble\Medical\Models;
use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Botble\Ecommerce\Enums\OrderStatusEnum;
use Botble\Ecommerce\Models\Customer;
use Botble\Medical\Models\Laboratories;
use App\Models\Address;
use App\Models\Area;
use App\Models\City;
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
            'lab_id',
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
    public function lab()
    {
        return $this->belongsTo(Laboratories::class, 'lab_id', 'id')->withDefault();
    }
    public function Address()
    {
        return $this->belongsTo(Address::class, 'address','id')->withDefault();
    }


    /**
     * @return string
     */
    public function getFullAddressAttribute()
    {
        return $this->Address->city->name.', '. $this->Address->area->name.' , '.$this->Address->address . ', ' . $this->Address->mobile.', ' . $this->Address->state;
    }
    protected $casts = [
        'status'  => OrderStatusEnum::class,
    ];
   
}
