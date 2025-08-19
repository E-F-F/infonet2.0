<?php

namespace Modules\IMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IMSVehicleModel extends Model
{
    use HasFactory;

    protected $table = 'ims_vehicle_model'; // ✅ Correct table

    protected $fillable = [
        'ims_vehicle_make_id',
        'name',
    ];

    /**
     * Relationship to vehicle make.
     */
    public function make()
    {
        return $this->belongsTo(IMSVehicleMake::class, 'ims_vehicle_make_id');
    }
}
