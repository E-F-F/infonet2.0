<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\HRMS\Database\Factories\HRMSStaffEmploymentFactory;

class HRMSStaffEmployment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'hrms_staff_employment';

    protected $fillable = [
        'branch_id',
        'hrms_designation_id',
        'hrms_leave_rank_id',
        'hrms_pay_group_id',
        'hrms_appraisal_type_id',
        'employee_number',
        'joining_date'
    ];
}
