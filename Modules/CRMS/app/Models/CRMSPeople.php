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
        'hrms_staff_id',
        'people_type',
        'people_status',
        'grading',
        'last_contact_date',
        'under_company_registration',
        'crms_company_id',
        // Personal Details
        'customer_name',
        'id_type',
        'id_number',
        'tin',
        'dob',
        'owner_phone',
        'home_no',
        'email',
        // Other Contact
        'contact_person_name',
        'contact_person_phone',
        'office_no',
        'fax_no',
        // Home Address
        'primary_address',
        'primary_postcode',
        'primary_city',
        'primary_state',
        'primary_country',
        // Postal Address
        'postal_address',
        'postal_postcode',
        'postal_city',
        'postal_state',
        'postal_country',
        // Others
        'zone',
        'crms_people_race_id',
        'religion',
        'crms_people_income_id',
        'marital_status',
        'crms_people_occupation_id',
        'crms_business_nature_id',
        'is_corporate',
        'lifestyle_interest',
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
     * Relationships Belongs To
     */
    public function staff()
    {
        return $this->belongsTo(HRMSStaff::class, 'hrms_staff_id');
    }

    public function company()
    {
        return $this->belongsTo(CRMSCompany::class, 'crms_company_id');
    }

    public function race()
    {
        return $this->belongsTo(CRMSPeopleRace::class, 'crms_people_race_id');
    }

    public function income()
    {
        return $this->belongsTo(CRMSPeopleIncome::class, 'crms_people_income_id');
    }

    public function occupation()
    {
        return $this->belongsTo(CRMSPeopleOccupation::class, 'crms_people_occupation_id');
    }

    public function businessNature()
    {
        return $this->belongsTo(CRMSBusinessNature::class, 'crms_business_nature_id');
    }

    /**
     * Relationships Has Many
     */
    // public function vehicleInfo()
    // {
    //     return $this->hasMany(CRMSVehicleInfo::class, 'crms_people_id');
    // }

    // public function followUps()
    // {
    //     return $this->hasMany(CRMSPeopleFollowUp::class, 'crms_people_id');
    // }

    // public function marketingInfo()
    // {
    //     return $this->hasMany(CRMSPeopleMarketingInfo::class, 'crms_people_id');
    // }

    // public function quotations()
    // {
    //     return $this->hasMany(CRMSQuotation::class, 'crms_people_id');
    // }
}
