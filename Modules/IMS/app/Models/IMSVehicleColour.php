<?php

namespace Modules\IMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IMSVehicleColour extends Model
{
    use HasFactory;

    protected $table = 'ims_vehicle_colour'; // ✅ Correct table name

    protected $fillable = [
        'name',
    ];
}
