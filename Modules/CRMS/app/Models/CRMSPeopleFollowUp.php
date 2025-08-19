<?php

namespace Modules\CRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\IMS\Models\IMSVehicleBodyType;
use Modules\IMS\Models\IMSVehicleColour;
use Modules\IMS\Models\IMSVehicleMake;
use Modules\IMS\Models\IMSVehicleModel;

class CRMSPeopleFollowUp extends Model
{
    use HasFactory;

    protected $table = 'crms_people_follow_up';

    protected $fillable = [
        'crms_people_id',
        'date',
        'type_of_prospect',
        'type',
        'vehicle_reg_no',
        'ims_vehicle_make_id',
        'ims_vehicle_model_id',
        'ims_vehicle_body_type_id',
        'ims_vehicle_colour_id',
        'customer_feedback',
        'next_action',
        'next_follow_up_date',
        'potential',
        'manager_comment',
        'notes',
    ];

    /**
     * Relationships
     */
    public function person()
    {
        return $this->belongsTo(CRMSPeople::class, 'crms_people_id');
    }

    public function vehicleMake()
    {
        return $this->belongsTo(IMSVehicleMake::class, 'ims_vehicle_make_id');
    }

    public function vehicleModel()
    {
        return $this->belongsTo(IMSVehicleModel::class, 'ims_vehicle_model_id');
    }

    public function vehicleBodyType()
    {
        return $this->belongsTo(IMSVehicleBodyType::class, 'ims_vehicle_body_type_id');
    }

    public function vehicleColour()
    {
        return $this->belongsTo(IMSVehicleColour::class, 'ims_vehicle_colour_id');
    }
}
