<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HRMSStaffEmploymentHistory extends Model
{
    use HasFactory;

    protected $table = 'hrms_staff_employment_history';

    protected $fillable = [
        'hrms_staff_id',
        'organization',
        'position',
        'start_date',
        'end_date',
        'comment',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(HRMSStaff::class, 'hrms_staff_id');
    }
}
