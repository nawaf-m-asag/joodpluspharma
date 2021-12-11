<?php

namespace Botble\Medical\Models;
use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Botble\Ecommerce\Enums\OrderStatusEnum;
use Botble\Ecommerce\Models\Customer;
use Illuminate\Support\Facades\DB;
use App\Models\Address;
use App\Models\Area;
use App\Models\City;
class Maintenance extends BaseModel
{

    use EnumCastable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'med_maintenance';

    /**
     * @var array
     */

    protected $fillable = [
            'side_name',
            'applicant_name',
            'device_name',
            'descrip_defect',
            'phone',
            'address',
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
