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
    protected $fillable = [
        'branch_id',
        'crms_people_id',
        'registration_no',
        'stock_no',
        'date_in',
        'date_to_hq',
        'colour',
        'accesories_std',
        'accesories_opt',
        'body',
        'chassis_no',
        'engine_no',
        'manufacture_year',
        'ims_vehicle_make_id',
        'ims_vehicle_model_id',
        'type',
        'rec_net_sp',
        'accesories_otrCost',
        'rec_otr_sp_stdAcc',
        'notes',
    ];

    protected $table = 'crms_people_vehicle_info';

    public function make()
    {
        return $this->belongsTo(\Modules\IMS\Models\IMSVehicleMake::class, 'ims_vehicle_make_id');
    }

    public function model()
    {
        return $this->belongsTo(\Modules\IMS\Models\IMSVehicleModel::class, 'ims_vehicle_model_id');
    }
}
