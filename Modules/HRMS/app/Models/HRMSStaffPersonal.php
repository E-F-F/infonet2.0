<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany; // Import HasMany for the relationship

/**
 * HRMSStaffPersonal Model
 *
 * This model represents the 'hrms_staff_personal' table, storing personal details of staff members.
 * It includes relationships to dependent children and the main staff record.
 */
class HRMSStaffPersonal extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hrms_staff_personal';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstName',
        'middleName',
        'lastName',
        'fullName',
        'dob',
        'gender',
        'marital_status',
        'blood_group',
        'religion',
        'race',
        'image_url',
        'bank_account_no',
        'bank_name',
        'bank_branch',
        'socso_no',
        'epf_no',
        'income_tax_no',
        'ic_no',
        'old_ic_no',
        'passport_no',
        'driving_license_no',
        'driving_license_category',
        'driving_license_expiry_date',
        'gdl_expiry_date',
        'work_permit_expiry_date',
        'father_name',
        'father_dob',
        'mother_name',
        'mother_dob',
        'spouse_name',
        'spouse_dob',
        'mobile_no',
        'work_no',
        'landline_no',
        'work_email',
        'other_email',
        'present_address',
        'present_city',
        'present_state',
        'permanent_address',
        'permanent_city',
        'permanent_state',
        'mailing_address',
        'emergency_contact',
        'emergency_relation',
        'emergency_landline_no',
        'emergency_work_no',
        'emergency_mobile_no',
        'emergency_address',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'dob' => 'date',
        'father_dob' => 'date',
        'mother_dob' => 'date',
        'spouse_dob' => 'date',
        // Note: driving_license_expiry_date, gdl_expiry_date, work_permit_expiry_date
        // are strings in migration, so not casting to date here unless explicitly needed.
        // If they store valid dates, consider adding them:
        // 'driving_license_expiry_date' => 'date',
        // 'gdl_expiry_date' => 'date',
        // 'work_permit_expiry_date' => 'date',
    ];

    /**
     * Get the main staff record associated with these personal details.
     * Assumes a one-to-one relationship where HRMSStaff has a foreign key to HRMSStaffPersonal.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function staff(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(HRMSStaff::class, 'hrms_staff_personal_id');
    }

    /**
     * Get the dependent children for the staff personal record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(HRMSStaffDependentChild::class, 'hrms_staff_personal_id');
    }
}
