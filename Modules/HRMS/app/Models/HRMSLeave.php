<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Branch; // Assuming Branch model is in App\Models or similar
/**
 * HRMSLeave Model
 *
 * This model represents the 'hrms_leave' table, storing staff leave applications.
 * It includes relationships to Branch, HRMSStaff, HRMSLeaveType, and staff who tracked the record.
 * It also supports soft deletes.
 */
class HRMSLeave extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hrms_leave';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'branch_id',
        'hrms_staff_id',
        'hrms_leave_type_id',
        'date_from',
        'session_from',
        'date_to',
        'session_to',
        'leave_purpose',
        'attachment_url',
        'status',
        'remarks',
        'created_by',
        'updated_by',
        'approved_by',
        'rejected_by',
        'created_at',
        'updated_at',
        'approved_at',
        'rejected_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
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
     * Get the branch that the leave record belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /**
     * Get the staff member who applied for the leave.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(HRMSStaff::class, 'hrms_staff_id');
    }

    /**
     * Get the type of leave.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(HRMSLeaveType::class, 'hrms_leave_type_id');
    }

    /**
     * Get the staff member who created this leave record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(HRMSStaff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this leave record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(HRMSStaff::class, 'updated_by');
    }

    /**
     * Get the staff member who approved this leave record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(HRMSStaff::class, 'approved_by');
    }

    /**
     * Get the staff member who rejected this leave record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rejecter(): BelongsTo
    {
        return $this->belongsTo(HRMSStaff::class, 'rejected_by');
    }
}
