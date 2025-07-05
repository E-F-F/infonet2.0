<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HRMSLeaveEntitlement extends Model
{
    use HasFactory;

    protected $table = 'hrms_leave_entitlement';

    protected $fillable = [
        'hrms_staff_id',
        'hrms_leave_type_id', // Crucial addition
        'entitled_days', // Total days allocated for this leave type
        'consumed_days', // Days already taken
        'remaining_days', // Calculated: entitled_days - consumed_days
        'year', // Or a period ID
    ];

    protected $casts = [
        'entitled_days' => 'float',
        'consumed_days' => 'float',
        'remaining_days' => 'float',
        'year' => 'integer',
    ];

    /**
     * Get the staff member associated with the entitlement.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(HRMSStaff::class, 'hrms_staff_id'); // Using User model for hrms_staff
    }

    /**
     * Get the leave type associated with the entitlement.
     */
    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(HrmsLeaveType::class, 'hrms_leave_type_id');
    }
}
