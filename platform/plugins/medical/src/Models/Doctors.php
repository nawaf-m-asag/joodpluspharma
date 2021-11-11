<?php

namespace Botble\Medical\Models;
use Botble\Base\Models\BaseModel;
use Botble\Medical\Models\Specialties;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Traits\EnumCastable;
class Doctors extends BaseModel
{

    use EnumCastable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'med_doctors';

    /**
     * @var array
     */

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'specialty_id',
        'status',
    ];

    public $timestamps = true;
     /**
     * @var array
     */
    public function specialty()
    {
        return $this->belongsTo(Specialties::class, 'specialty_id', 'id')->withDefault();
    }
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
