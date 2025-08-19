<?php

namespace Modules\IMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IMSVehicleMake extends Model
{
    use HasFactory;

    protected $table = 'ims_vehicle_make'; // ✅ Correct table

    protected $fillable = [
        'name',
    ];
}
