<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HRMSStaffRosterGroupAssignment extends Model
{
    use HasFactory;

    protected $table = 'hrms_staff_roster_group_assignment';

    protected $fillable = [
        'hrms_staff_id',
        'roster_group_id',
        'effective_date',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(HRMSStaff::class, 'hrms_staff_id');
    }

    public function rosterGroup(): BelongsTo
    {
        return $this->belongsTo(HRMSRosterGroup::class, 'roster_group_id');
    }
}
