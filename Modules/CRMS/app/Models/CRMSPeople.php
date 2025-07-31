<?php

namespace Modules\CRMS\Models;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\HRMS\Models\HRMSStaff;

class CRMSPeople extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'crms_people';

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
        'gst_reg_no',
        'hrms_staff_id',
        'office_no',
        'home_no',
        'phone_no',
        'user_name',
        'user_phone_no',
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
        'crms_people_race_id',
        'religion',
        'crms_people_income_id',
        'marital_status',
        'company_size',
        'sector',
        'crms_business_nature_id',
        'crms_people_occupation_id',
        'grading',
        'is_corporate',
        'lifestyle_interest',
        'last_contact_date',
        'link_customer',
        'link_customer_type',
        'terms',
        'price_scheme',
        'notes',
        'log',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'under_company_registration' => 'boolean',
        'is_corporate' => 'boolean',
        'dob' => 'date',
        'last_contact_date' => 'date',
    ];

    /**
     * Relationships
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function corporateGroup()
    {
        return $this->belongsTo(CRMSCorporateGroup::class, 'crms_corporate_group_id');
    }

    public function race()
    {
        return $this->belongsTo(CRMSPeopleRace::class, 'crms_people_race_id');
    }

    public function income()
    {
        return $this->belongsTo(CRMSPeopleIncome::class, 'crms_people_income_id');
    }

    public function businessNature()
    {
        return $this->belongsTo(CRMSBusinessNature::class, 'crms_business_nature_id');
    }

    public function occupation()
    {
        return $this->belongsTo(CRMSPeopleOccupation::class, 'crms_people_occupation_id');
    }

    public function staff()
    {
        return $this->belongsTo(HRMSStaff::class, 'hrms_staff_id');
    }

    public function vehicleInfo()
    {
        return $this->hasMany(CRMSVehicleInfo::class, 'crms_people_id');
    }

    public function followUps()
    {
        return $this->hasMany(CRMSPeopleFollowUp::class, 'crms_people_id');
    }

    public function marketingInfo()
    {
        return $this->hasMany(CRMSPeopleMarketingInfo::class, 'crms_people_id');
    }

    public function quotations()
    {
        return $this->hasMany(CRMSQuotation::class, 'crms_people_id');
    }
}
