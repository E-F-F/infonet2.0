<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import BelongsTo for relationships
use App\Models\Branch; // Assuming Branch model is in App\Models or similar
use Modules\HRMS\Models\HRMSDesignation;
use Modules\HRMS\Models\HRMSLeaveRank;
use Modules\HRMS\Models\HRMSPayGroup;
use Modules\HRMS\Models\HRMSAppraisalType;

/**
 * HRMSStaffEmployment Model
 *
 * This model represents the 'hrms_staff_employment' table, storing employment details.
 * It includes relationships to various HRMS lookup tables and the main staff record.
 */
class HRMSStaffEmployment extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hrms_staff_employment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'branch_id',
        'hrms_designation_id',
        'hrms_leave_rank_id',
        'hrms_pay_group_id',
        'hrms_appraisal_type_id',
        'employee_number',
        'joining_date',
        'confirmation_date',
        'relieving_date',
        'training_period',
        'probation_period',
        'notice_period',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'joining_date' => 'date',
        'confirmation_date' => 'date',
        'relieving_date' => 'date',
        'training_period' => 'integer',
        'probation_period' => 'integer',
        'notice_period' => 'integer',
    ];

    /**
     * Get the branch that the staff employment record belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /**
     * Get the designation that the staff employment record belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function designation(): BelongsTo
    {
        return $this->belongsTo(HRMSDesignation::class, 'hrms_designation_id');
    }

    /**
     * Get the leave rank that the staff employment record belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function leaveRank(): BelongsTo
    {
        return $this->belongsTo(HRMSLeaveRank::class, 'hrms_leave_rank_id');
    }

    /**
     * Get the pay group that the staff employment record belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payGroup(): BelongsTo
    {
        return $this->belongsTo(HRMSPayGroup::class, 'hrms_pay_group_id');
    }

    /**
     * Get the appraisal type that the staff employment record belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function appraisalType(): BelongsTo
    {
        return $this->belongsTo(HRMSAppraisalType::class, 'hrms_appraisal_type_id');
    }

    /**
     * Get the main staff record associated with this employment record.
     * Assumes a one-to-one relationship where HRMSStaff has a foreign key to HRMSStaffEmployment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function staff(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(HRMSStaff::class, 'hrms_staff_employment_id');
    }
}