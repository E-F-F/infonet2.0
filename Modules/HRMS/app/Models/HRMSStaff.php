<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\HRMS\Database\Factories\HRMSStaffFactory;

class HRMSStaff extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'hrms_staff';
    protected $fillable = [
        'staff_auth_id',
        'hrms_staff_personal_id',
        'hrms_staff_employment_id'
    ];
}
