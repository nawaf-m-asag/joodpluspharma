<?php

namespace Botble\Medical\Models;
use Botble\Base\Models\BaseModel;
use Botble\Ecommerce\Models\Customer;
use Botble\Base\Traits\EnumCastable;
use Botble\Ecommerce\Enums\OrderStatusEnum;
use App\Models\Address;
use App\Models\Area;
use App\Models\City;
class Prescriptions extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'prescriptions';

    /**
     * @var array
     */
 
    protected $fillable = [
        'user_id',
        'status',
        'notes',
        'address_id',
        'image_file',
        'file'

    ];

    public $timestamps = true;
    protected $casts = [
        'status'          => OrderStatusEnum::class,
    ];
    
    public function user()
    {
        return $this->belongsTo(Customer::class, 'user_id', 'id')->withDefault();
    }
    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id','id')->withDefault();
    }


    /**
     * @return string
     */
    public function getFullAddressAttribute()
    {
        return $this->address->city->name.', '. $this->address->area->name.' , '.$this->address->address . ', ' . $this->address->mobile.', ' . $this->address->state;
    }



    
}
