<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * HRMSOvertime Model
 *
 * This model represents the 'hrms_overtime' table, storing staff overtime records.
 * It includes a relationship to HRMSStaff and handles activity logs.
 */
class HRMSOvertime extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hrms_overtime';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hrms_staff_id',
        'overtime_date',
        'overtime_clockIn',
        'overtime_clockOut',
        'overtime_total_hours',
        'overtime_status',
        'activity_logs',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'overtime_date' => 'date',
        'overtime_clockIn' => 'datetime',
        'overtime_clockOut' => 'datetime',
        'overtime_total_hours' => 'float',
        'activity_logs' => 'array', // Cast JSON column to array
    ];

    /**
     * Get the staff member associated with this overtime record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(HRMSStaff::class, 'hrms_staff_id');
    }
}
