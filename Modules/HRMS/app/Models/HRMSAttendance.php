<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * HRMSAttendance Model
 *
 * This model represents the 'hrms_attendance' table, storing staff attendance records.
 * It includes a relationship to HRMSStaff.
 */
class HRMSAttendance extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hrms_attendance';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hrms_staff_id',
        'attendance_date',
        'morning_clockIn',
        'morning_clockOut',
        'morning_status',
        'afternoon_clockIn',
        'afternoon_clockOut',
        'afternoon_status',
        'total_working_hours',
        'remark',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'attendance_date' => 'date',
        'morning_clockIn' => 'datetime',
        'morning_clockOut' => 'datetime',
        'afternoon_clockIn' => 'datetime',
        'afternoon_clockOut' => 'datetime',
        'total_working_hours' => 'float',
    ];

    /**
     * Get the staff member associated with this attendance record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(HRMSStaff::class, 'hrms_staff_id');
    }
}
