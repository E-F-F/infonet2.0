<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\HRMS\Database\Factories\HRMSAttendanceFactory;

class HRMSAttendance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'hrms_attendance';

    protected $fillable = [
        'hrms_staff_id',
        'attendance_date',
        'morning_clockIn',
        'morning_clockOut',
        'afternoon_clockIn',
        'afternoon_clockOut',
        'afternoon_status',
        'total_working_hours',
        'remark',
    ];
}
