<?php

namespace Modules\CRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\CRMS\Database\Factories\CRMSVehicleInfoFactory;

class CRMSVehicleInfo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): CRMSVehicleInfoFactory
    // {
    //     // return CRMSVehicleInfoFactory::new();
    // }
}
