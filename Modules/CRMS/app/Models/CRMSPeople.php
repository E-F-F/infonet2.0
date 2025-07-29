<?php

namespace Modules\CRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CRMSPeople extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'branch_id',
        'status',
        'under_company_registration',
        'customer_name',
        'company_name',
        'crms_corporate_group_id',
        'id_type',
        'id_number',
        'tin',
        'sst_reg_no',
        'sa',
        'telephone_o',
        'telephone_h',
        'owner_hp_no',
        'user_name',
        'user_hp_no',
        'fax_no',
        'email',
        'postal_address',
        'postal_postcode',
        'postal_city',
        'postal_state',
        'postal_country',
        'primary_address',
        'primary_postcode',
        'primary_city',
        'primary_state',
        'primary_country',
        'zone',
        'city',
        'state',
        'dob',
        'race',
        'religion',
        'monthly_house_income',
        'marital_status',
        'company_size',
        'sector',
        'nature_of_business',
        'occupation',
        'grading',
        'is_corporate',
        'lifestyle_interest',
        'last_contact_date',
        'link_customer',
        'link_customer_type',
        'terms',
        'price_scheme',
        'notes',
    ];

    /**
     * The table associated with the model.
     */
    protected $table = 'crms_people';

    public function vehicleInfo()
    {
        return $this->hasMany('Modules\CRMS\Models\CRMSVehicleInfo', 'crms_people_id');
    }
}
