<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
/**
 * HRMSPayroll Model
 *
 * This model represents the 'hrms_payroll' table, storing payroll batch information.
 * It includes relationships to Pay Group, Pay Batch Type, and staff who tracked the record.
 * It also supports soft deletes.
 */
class HRMSPayroll extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hrms_payroll';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hrms_pay_group_id',
        'hrms_pay_batch_type_id',
        'full_work_day',
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
        'full_work_day' => 'integer',
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
     * Get the pay group that the payroll record belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payGroup(): BelongsTo
    {
        return $this->belongsTo(HRMSPayGroup::class, 'hrms_pay_group_id');
    }

    /**
     * Get the pay batch type that the payroll record belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payBatchType(): BelongsTo
    {
        return $this->belongsTo(HRMSPayBatchType::class, 'hrms_pay_batch_type_id');
    }

    /**
     * Get the staff member who created this payroll record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(HRMSStaff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this payroll record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(HRMSStaff::class, 'updated_by');
    }

    /**
     * Get the staff member who approved this payroll record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(HRMSStaff::class, 'approved_by');
    }

    /**
     * Get the staff member who rejected this payroll record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rejecter(): BelongsTo
    {
        return $this->belongsTo(HRMSStaff::class, 'rejected_by');
    }
}