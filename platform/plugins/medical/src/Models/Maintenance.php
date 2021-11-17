<?php

namespace Botble\Medical\Models;
use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Botble\Ecommerce\Enums\OrderStatusEnum;
use Botble\Ecommerce\Models\Customer;
use Illuminate\Support\Facades\DB;
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
    protected $casts = [
        'status'  => OrderStatusEnum::class,
    ];
   
}
