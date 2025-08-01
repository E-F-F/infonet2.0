<?php

namespace Modules\CRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\CRMS\Database\Factories\CRMSPeopleRaceFactory;

class CRMSPeopleRace extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'crms_people_follow_up_id',
        'status',
        'sa_remark',
        'branch_id',
        'hrms_staff_id',
        'type',
        'ims_vehicle_make_id',
        'ims_vehicle_model_id',
        'ims_vehicle_colour_id',
        'ims_vehicle_body_type_id',
        'remark',
        'status_date',
        'quotation',
        'vso_no',
        'booking_date',
        'lap_date',
        'proposed_inv',
        'proposed_bank_loan_ammount',
        'proposed_tenure',
        'proposed_bank',
        'assigned_to',
    ];

    /**
     * The table associated with the model.
     */
    protected $table = 'crms_people_booking';

    public function people()
    {
        return $this->hasMany(CRMSPeople::class, 'crms_people_race_id');
    }
}
