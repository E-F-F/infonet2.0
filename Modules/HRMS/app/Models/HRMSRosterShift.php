<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HRMSRosterShift extends Model
{
    use HasFactory;

    protected $table = 'hrms_roster_shift';

    protected $fillable = [
        'name',
        'time_in',
        'time_out',
        'break_time_in',
        'break_time_out',
        'has_lunch_break',
        'break_minutes',
        'second_break_time_in',
        'second_break_time_out',
        'second_break_minutes',
        'is_lunch_break',
        'ot_time_in',
        'ot_time_out',
        'ot_work_minutes',
        'full_shift',
        'flexi',
        'late_offset_ot',
        'alt_shift',
        'ot1_component',
        'ot2_component',
        'ot2_component_hours',
        'late_in_rounding_minutes',
        'early_out_rounding_minutes',
        'break_late_in_rounding_minutes',
        'break_late_in_minimum_minutes',
        'ot_round_down_minutes',
        'ot_round_up_adj_minutes',
        'ot_minimum_minutes',
        'ot_days',
        'type_for_leave',
        'status',
        'branch_id',
        'allowed_thumbprint_once',
        'background_color',
        'remarks',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Branch::class, 'branch_id');
    }
}
