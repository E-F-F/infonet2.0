<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * HRMSLeaveType Model
 *
 * This model represents the 'hrms_leave_type' table.
 * It supports soft deletes.
 */
class HRMSLeaveType extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hrms_leave_type';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'default_no_of_days',
        'status',
        'earned_rules',
        'need_blocking',
        'leave_model', //boolean value indicating whether has it's a leave model or not make another api that list all leave type if leave model is true
        'allow_carry_forward', // New column
        'require_attachment',
        'apply_by_hours',
        'apply_within_days',
        'background_color',
        'remarks',
        'replacement_shift',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'need_blocking' => 'boolean',
        'leave_model' => 'boolean',
        'allow_carry_forward' => 'boolean', // Cast new column to boolean
        'require_attachment' => 'boolean',
        'apply_by_hours' => 'boolean',
        'default_no_of_days' => 'integer',
        'apply_within_days' => 'integer',
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
     * Get the leave applications associated with this leave type.
     */
    public function leaves(): HasMany
    {
        return $this->hasMany(HrmsLeave::class, 'hrms_leave_type_id');
    }

    /**
     * Get the leave models (entitlement rules) for this leave type.
     */
    public function leaveModels(): HasMany
    {
        return $this->hasMany(HrmsLeaveModel::class, 'hrms_leave_type_id');
    }

    /**
     * Get the leave entitlements associated with this leave type.
     */
    public function entitlements(): HasMany
    {
        return $this->hasMany(HrmsLeaveEntitlement::class, 'hrms_leave_type_id');
    }

    /**
     * Get the leave adjustments associated with this leave type.
     */
    public function adjustments(): HasMany
    {
        return $this->hasMany(HrmsLeaveAdjustment::class, 'hrms_leave_type_id');
    }
}
