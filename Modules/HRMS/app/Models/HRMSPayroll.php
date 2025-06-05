<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\HRMS\Database\Factories\HRMSPayrollFactory;

class HRMSPayroll extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'hrms_payroll';

    protected $fillable = [
        'hrms_pay_group_id',
        'hrms_pay_batch_type_id',
        'full_work_day',
        'remarks',
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
