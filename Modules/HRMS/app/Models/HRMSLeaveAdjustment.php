<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HRMSLeaveAdjustment extends Model
{
    use HasFactory;

    protected $table = 'hrms_leave_adjustment';

    protected $fillable = [
        'hrms_staff_id',
        'hrms_leave_type_id',
        'adjustment_reason_id',
        'days',
        'effective_date',
        'remarks',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'days' => 'integer', // Assuming adjustments are usually whole days, adjust if float is common
    ];

    /**
     * Get the staff member the adjustment is for.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(HRMSStaff::class, 'hrms_staff_id'); // Using User model for hrms_staff
    }

    /**
     * Get the leave type the adjustment affects.
     */
    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(HrmsLeaveType::class, 'hrms_leave_type_id');
    }

    /**
     * Get the reason for the adjustment.
     */
    public function adjustmentReason(): BelongsTo
    {
        return $this->belongsTo(HrmsLeaveAdjustmentReason::class, 'adjustment_reason_id');
    }
}
