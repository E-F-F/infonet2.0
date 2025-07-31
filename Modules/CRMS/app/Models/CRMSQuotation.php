<?php

namespace Modules\CRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CRMSQuotation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'status',
        'branch',
        'doc_no',
        'doc_date',
        'crms_people_id',
        'contact',
        'address',
        'vehicle_reg_no',
        'ims_vehicle_make_id',
        'ims_vehicle_model_id',
        'ims_vehicle_colour_id',
        'ims_vehicle_body_type_id',
        'year_make',
        'body_size',
        'displacement',
        'max_output',
        'gross_vehicle_weight',
        'free_service',
        'warranty_period',
        'estimated_delivery',
        'special_modification',
        'remark_to_quotation',
        'sa',
        'sa_personal_remark',
        'crms_people_marketing_info_id',
        'selling_price_c/w_tax_freight_with_std_accessories',
        'number_plate',
        'bed_liner',
        'rollbar',
        'bug_protector',
        'door_visor',
        'side_step',
        'spare_tyre_lock',
        'alarm_centre_lock',
    ];

    /**
     * The table associated with the model.
     */
    protected $table = 'crms_business_nature';
}
