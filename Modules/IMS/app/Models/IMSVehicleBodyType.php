<?php

namespace Modules\IMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IMSVehicleBodyType extends Model
{
    use HasFactory;

    protected $table = 'ims_vehicle_body_type'; // ✅ Correct table

    protected $fillable = [
        'name',
    ];
}
