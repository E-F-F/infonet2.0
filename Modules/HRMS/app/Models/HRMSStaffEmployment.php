<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\HRMS\Database\Factories\HRMSStaffEmploymentFactory;
use App\Models\Branch;

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

    public function staff()
    {
        return $this->hasOne(HRMSStaff::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function designation()
    {
        return $this->belongsTo(HRMSDesignation::class, 'hrms_designation_id');
    }

    public function leaveRank()
    {
        return $this->belongsTo(HRMSLeaveRank::class, 'hrms_leave_rank_id');
    }

    public function payGroup()
    {
        return $this->belongsTo(HRMSPayGroup::class, 'hrms_pay_group_id');
    }

    public function appraisalType()
    {
        return $this->belongsTo(HRMSAppraisalType::class, 'hrms_appraisal_type_id');
    }
}
