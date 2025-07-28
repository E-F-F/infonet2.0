<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HRMSAttendance extends Model
{
    use HasFactory;

    protected $table = 'hrms_attendance';

    protected $fillable = [
        'hrms_staff_id',
        'attendance_date',
        'day_name',
        'time_in',
        'break_time_out',
        'break_time_in',
        'time_out',
        'late_time_in',
        'early_time_out',
        'overtime_minutes',
        'break_time_total',
        'time_in_status',
        'time_out_status',
        'total_working_hours',
        'remark',
    ];

    protected $casts = [
        'attendance_date'     => 'date',
        'time_in'             => 'datetime:H:i:s',
        'break_time_out'      => 'datetime:H:i:s',
        'break_time_in'       => 'datetime:H:i:s',
        'time_out'            => 'datetime:H:i:s',
        'late_time_in'        => 'float',
        'early_time_out'      => 'float',
        'overtime_minutes'    => 'float',
        'break_time_total'    => 'float',
        'total_working_hours' => 'float',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(HRMSStaff::class, 'hrms_staff_id');
    }
}
