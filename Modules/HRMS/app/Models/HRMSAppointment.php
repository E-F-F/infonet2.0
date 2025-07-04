<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * HRMSAppointment Model
 *
 * This model represents the 'hrms_appointment' table, storing appointment details.
 * It includes relationships to the recipient and staff who tracked the record.
 * It also supports soft deletes.
 */
class HRMSAppointment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hrms_appointment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'appointment_subject',
        'appointment_description',
        'appointment_recipient',
        'appointment_date',
        'appointment_start_time',
        'appointment_end_time',
        'appointment_remark',
        'appointment_status',
        'appointment_reviewer_remark',
        'created_by',
        'created_at', // Explicitly include if not handled by default timestamps
        'updated_by',
        'updated_at', // Explicitly include if not handled by default timestamps
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
        'activity_logs',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'appointment_date' => 'date',
        'appointment_start_time' => 'datetime', // Cast to datetime for time fields
        'appointment_end_time' => 'datetime',   // Cast to datetime for time fields
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'activity_logs' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array<int, string>
     */
    protected $dates = [
        'deleted_at',
    ];

    /**
     * Get the staff member who is the recipient of the appointment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(HRMSStaff::class, 'appointment_recipient');
    }

    /**
     * Get the staff member who created this appointment record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(HRMSStaff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this appointment record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(HRMSStaff::class, 'updated_by');
    }

    /**
     * Get the staff member who approved this appointment record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(HRMSStaff::class, 'approved_by');
    }

    /**
     * Get the staff member who rejected this appointment record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rejecter(): BelongsTo
    {
        return $this->belongsTo(HRMSStaff::class, 'rejected_by');
    }
}
