<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\HRMS\Database\Factories\HRMSStaffPersonalFactory;

class HRMSStaffPersonal extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'hrms_staff_personal';

    protected $fillable = [
        'firstName',
        'middleName',
        'lastName',
        'fullName',
        'ic_no',
        'dob',
        'gender',
        'marital_status',
        'nationality',
        'religion',
        'race',
        'blood_group',
        'work_email',
        'phone_number',
        'home_address',
        'image_url',
    ];

    public function staff()
    {
        return $this->hasOne(HRMSStaff::class);
    }
}
