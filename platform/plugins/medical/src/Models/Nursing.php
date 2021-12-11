<?php

namespace Botble\Medical\Models;
use Botble\Base\Models\BaseModel;
use Botble\Medical\Models\Doctors;
use Botble\Base\Traits\EnumCastable;
use Botble\Ecommerce\Enums\OrderStatusEnum;
use Botble\Ecommerce\Models\Customer;
use Illuminate\Support\Facades\DB;
use App\Models\Address;
use App\Models\Area;
use App\Models\City;
class Nursing extends BaseModel
{

    use EnumCastable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'med_nursing_servicrs';

    /**
     * @var array
     */

    protected $fillable = [
        'p_name',
        'p_age',
        'p_sex',
        'doctor_id',
        'address',
        'attachedFile',
        'user_id',
        'status',
    ];

    public $timestamps = true;
     /**
     * @var array
     */
    public function doctor()
    {
        return $this->belongsTo(Doctors::class, 'doctor_id', 'id')->withDefault();
    }
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
    public function getAllSelectedServes()
    {
        $query= DB::table('med_selected_services as ss')->select('ms.name')->where('ss.nursing_servicrs_id',$this->id)
        ->join('med_services as ms','ss.services_id','=','ms.id')->get();
        $string="";
        foreach ($query as $key => $value) {

            $string=$string.'<span class="badge badge-success m-2">'.$value->name.'</span>';
      
        }
        return $string;
    }
}
