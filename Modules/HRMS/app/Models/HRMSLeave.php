<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\HRMS\Database\Factories\HRMSLeaveFactory;

class HRMSLeave extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'hrms_leave';

    protected $fillable = [
        'branch_id',
        'hrms_staff_id',
        'hrms_leave_type_id',
        'date_from',
        'session_from',
        'date_to',
        'session_to',
        'leave_purpose',
        'attachment_url',
        'status',
        'created_by',
        'updated_by',
        'approved_by',
        'rejected_by',
        'created_at',
        'updated_at',
        'approved_at',
        'rejected_at',
    ];
}
