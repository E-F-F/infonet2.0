<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HRMSRoster extends Model
{
    use HasFactory;

    protected $table = 'hrms_roster';

    protected $fillable = [
        'branch_id',
        'year',
        'roster_group_id',

        'default_roster_shift',
        'sunday_shift',
        'public_holiday_shift',
        'company_half_off_day_shift',

        'sunday_shift',
        'monday_shift',
        'tuesday_shift',
        'wednesday_shift',
        'thursday_shift',
        'friday_shift',
        'saturday_shift',

        'effective_date',
        'status',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Branch::class, 'branch_id');
    }

    public function rosterDay(): HasMany
    {
        return $this->hasMany(HRMSRosterDayAssignments::class, 'roster_id');
    }

    public function rosterGroup(): BelongsTo
    {
        return $this->belongsTo(HRMSRosterGroup::class, 'roster_group_id');
    }

    public function defaultShift(): BelongsTo
    {
        return $this->belongsTo(HRMSRosterShift::class, 'default_roster_shift');
    }

    public function sundayShift(): BelongsTo
    {
        return $this->belongsTo(HRMSRosterShift::class, 'sunday_shift');
    }

    public function publicHolidayShift(): BelongsTo
    {
        return $this->belongsTo(HRMSRosterShift::class, 'public_holiday_shift');
    }

    public function companyHalfOffDayShift(): BelongsTo
    {
        return $this->belongsTo(HRMSRosterShift::class, 'company_half_off_day_shift');
    }

    // Optional: Relationships for each weekday shift
    public function mondayShift(): BelongsTo
    {
        return $this->belongsTo(HRMSRosterShift::class, 'monday_shift');
    }
    public function tuesdayShift(): BelongsTo
    {
        return $this->belongsTo(HRMSRosterShift::class, 'tuesday_shift');
    }
    public function wednesdayShift(): BelongsTo
    {
        return $this->belongsTo(HRMSRosterShift::class, 'wednesday_shift');
    }
    public function thursdayShift(): BelongsTo
    {
        return $this->belongsTo(HRMSRosterShift::class, 'thursday_shift');
    }
    public function fridayShift(): BelongsTo
    {
        return $this->belongsTo(HRMSRosterShift::class, 'friday_shift');
    }
    public function saturdayShift(): BelongsTo
    {
        return $this->belongsTo(HRMSRosterShift::class, 'saturday_shift');
    }
}
