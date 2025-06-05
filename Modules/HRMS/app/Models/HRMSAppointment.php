<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\HRMS\Database\Factories\HRMSAppointmentFactory;

class HRMSAppointment extends Model
{
    use HasFactory;

    protected $table = 'hrms_appointment';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'appointment_subject',
        'appointment_description',
        'appointment_recipient',
        'appointment_date',
        'appointment_start_time',
        'appointment_end_time',
        'appointment_remark',
        'appointment_status',
        'appointment_reviewer_remark',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
        'activity_logs',
        'is_active',
    ];

    // protected static function newFactory(): HRMSAppointmentFactory
    // {
    //     // return HRMSAppointmentFactory::new();
    // }
}
