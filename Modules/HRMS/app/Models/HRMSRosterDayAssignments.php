<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import BelongsTo for relationships
use Carbon\Carbon; // Import Carbon for date casting

class HRMSRosterDayAssignments extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hrms_roster_day_assignments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'roster_id',
        'roster_date',
        'day_type',
        'shift_id',
        'is_override',
        'hrms_staff_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'roster_date' => 'date', // Automatically cast roster_date to a Carbon instance
        'is_override' => 'boolean', // Cast is_override to a boolean
    ];

    /**
     * Get the roster that owns the day assignment.
     * Assumes an HrmsRoster model exists for the 'hrms_roster' table.
     *
     * @return BelongsTo
     */
    public function roster(): BelongsTo
    {
        return $this->belongsTo(HRMSRoster::class, 'roster_id');
    }

    /**
     * Get the shift associated with the day assignment.
     * Assumes an HrmsRosterShift model exists for the 'hrms_roster_shift' table.
     *
     * @return BelongsTo
     */
    public function shift(): BelongsTo
    {
        return $this->belongsTo(HRMSRosterShift::class, 'shift_id');
    }

    /**
     * Get the staff member associated with the day assignment.
     * This relationship is optional as hrms_staff_id is nullable.
     * Assumes an HrmsStaff model exists for the 'hrms_staff' table.
     *
     * @return BelongsTo
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(HRMSStaff::class, 'hrms_staff_id');
    }

    /**
     * Mutator for the 'day_type' attribute.
     * Ensures the day_type is one of the allowed enum values.
     * You might want to add validation rules in your FormRequest or controller
     * to enforce this more strictly before saving.
     *
     * @param string $value
     * @return void
     */
    public function setDayTypeAttribute(string $value): void
    {
        $allowedTypes = ['workday', 'public_holiday', 'offday', 'company_halfoffday'];
        if (!in_array($value, $allowedTypes)) {
            throw new \InvalidArgumentException("Invalid day_type value: {$value}");
        }
        $this->attributes['day_type'] = $value;
    }
}
