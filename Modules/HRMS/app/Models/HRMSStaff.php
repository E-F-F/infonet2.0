<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import BelongsTo for relationships
use Illuminate\Database\Eloquent\Relations\HasMany; // This is the problematic part if it's not the correct class
// Assuming StaffAuth is in the App\Models namespace or similar
use App\Models\StaffAuth; // Adjust this namespace if your StaffAuth model is elsewhere
use Modules\HRMS\Models\HRMSStaffPersonal;
use Modules\HRMS\Models\HRMSStaffEmployment;


/**
 * HRMSStaff Model
 *
 * This model represents the central 'hrms_staff' table, linking authentication,
 * personal, and employment details.
 */
class HRMSStaff extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hrms_staff';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'staff_auth_id',
        'hrms_staff_personal_id',
        'hrms_staff_employment_id',
    ];

    /**
     * Get the authentication record associated with the staff.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function auth(): BelongsTo
    {
        return $this->belongsTo(StaffAuth::class, 'staff_auth_id');
    }

    /**
     * Get the personal details record associated with the staff.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function personal(): BelongsTo
    {
        return $this->belongsTo(HRMSStaffPersonal::class, 'hrms_staff_personal_id');
    }

    /**
     * Get the employment details record associated with the staff.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employment(): BelongsTo
    {
        return $this->belongsTo(HRMSStaffEmployment::class, 'hrms_staff_employment_id');
    }

    /**
     * Get the leave applications submitted by the staff.
     */
    public function leavesApplied(): HasMany
    {
        return $this->hasMany(HRMSLeave::class, 'hrms_staff_id');
    }

    /**
     * Get the leave applications created by the staff.
     */
    public function leavesCreated(): HasMany
    {
        return $this->hasMany(HRMSLeave::class, 'created_by');
    }

    /**
     * Get the leave applications updated by the staff.
     */
    public function leavesUpdated(): HasMany
    {
        return $this->hasMany(HRMSLeave::class, 'updated_by');
    }

    /**
     * Get the leave applications approved by the staff.
     */
    public function leavesApproved(): HasMany
    {
        return $this->hasMany(HRMSLeave::class, 'approved_by');
    }

    /**
     * Get the leave applications rejected by the staff.
     */
    public function leavesRejected(): HasMany
    {
        return $this->hasMany(HRMSLeave::class, 'rejected_by');
    }

    /**
     * Get the leave entitlements for the staff.
     */
    public function leaveEntitlements(): HasMany
    {
        return $this->hasMany(HRMSLeaveEntitlement::class, 'hrms_staff_id');
    }

    /**
     * Get the leave adjustments for the staff.
     */
    public function leaveAdjustments(): HasMany
    {
        return $this->hasMany(HRMSLeaveAdjustment::class, 'hrms_staff_id');
    }

    /**
     * Get the staff's full name from their personal details.
     * This is an accessor that allows you to call $staff->full_name.
     *
     * @return string|null
     */
    public function getFullNameAttribute(): ?string
    {
        return $this->personal ? $this->personal->fullName : null;
    }

    /**
     * Get the staff's employee number from their employment details.
     * This is an accessor that allows you to call $staff->employee_number.
     *
     * @return string|null
     */
    public function getEmployeeNumberAttribute(): ?string
    {
        return $this->employment ? $this->employment->employee_number : null;
    }

    /**
     * Get the staff's roster group id from their employment details.
     * This is an accessor that allows you to call $staff->roster_group.
     *
     * @return string|null
     */
    public function getRosterGroupAttribute(): ?int
    {
        return $this->employment ? $this->employment->hrms_roster_group_id : null;
    }

    /**
     * Get a string representation of the staff (e.g., for debugging).
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->fullName ?: 'Staff (ID: ' . $this->id . ')';
    }

    /**
     * Get the staff's qualifications.
     */
    public function qualifications(): HasMany
    {
        return $this->hasMany(HRMSStaffQualification::class, 'hrms_staff_id');
    }

    public function trainingParticipants()
    {
        return $this->hasMany(HRMSTrainingParticipant::class, 'hrms_staff_id');
    }
}
