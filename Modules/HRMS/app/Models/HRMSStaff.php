<?php

namespace Modules\HRMS\Models;

use App\Models\StaffAuth;
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

    public function auth()
    {
        return $this->belongsTo(StaffAuth::class, 'staff_auth_id');
    }

    public function personal()
    {
        return $this->belongsTo(HRMSStaffPersonal::class, 'hrms_staff_personal_id');
    }

    public function employment()
    {
        return $this->belongsTo(HRMSStaffEmployment::class, 'hrms_staff_employment_id');
    }
}
