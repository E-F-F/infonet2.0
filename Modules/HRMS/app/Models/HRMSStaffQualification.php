<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HRMSStaffQualification extends Model
{
    use HasFactory;

    protected $table = 'hrms_staff_qualifications';

    protected $fillable = [
        'hrms_staff_id',
        'qualification',
        'institution',
        'start_date',
        'end_date',
        'marks_grade',
    ];

    /**
     * Relationship: Qualification belongs to a Staff
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(HRMSStaff::class, 'hrms_staff_id');
    }
}
