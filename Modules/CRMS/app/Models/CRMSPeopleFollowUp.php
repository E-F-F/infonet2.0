<?php

namespace Modules\CRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CRMSPeopleFollowUp extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
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
     * The table associated with the model.
     */
    protected $table = 'crms_people_follow_up';
}
