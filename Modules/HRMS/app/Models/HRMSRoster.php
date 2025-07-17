<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HRMSRoster extends Model
{
    use HasFactory;

    protected $table = 'hrms_roster';

    protected $fillable = [
        'branch_id',
        'year',
        'roster_group_id',
        'default_roster_shift_workday',
        'default_roster_shift_public_holiday',
        'default_roster_shift_offday',
        'default_roster_shift_company_halfoffday',
        'sunday_shift_workday',
        'sunday_shift_public_holiday',
        'sunday_shift_offday',
        'sunday_shift_company_halfoffday',
        'monday_shift_workday',
        'monday_shift_public_holiday',
        'monday_shift_offday',
        'monday_shift_company_halfoffday',
        'tuesday_shift_workday',
        'tuesday_shift_public_holiday',
        'tuesday_shift_offday',
        'tuesday_shift_company_halfoffday',
        'wednesday_shift_workday',
        'wednesday_shift_public_holiday',
        'wednesday_shift_offday',
        'wednesday_shift_company_halfoffday',
        'thursday_shift_workday',
        'thursday_shift_public_holiday',
        'thursday_shift_offday',
        'thursday_shift_company_halfoffday',
        'friday_shift_workday',
        'friday_shift_public_holiday',
        'friday_shift_offday',
        'friday_shift_company_halfoffday',
        'saturday_shift_workday',
        'saturday_shift_public_holiday',
        'saturday_shift_offday',
        'saturday_shift_company_halfoffday',
        'effective_date',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Branch::class, 'branch_id');
    }
}
