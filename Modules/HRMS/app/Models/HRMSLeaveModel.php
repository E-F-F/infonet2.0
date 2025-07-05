<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes; // Import the SoftDeletes trait
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * HRMSLeaveRank Model
 *
 * This model represents the 'hrms_leave_rank' table.
 * It supports soft deletes.
 */
class HRMSLeaveModel extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hrms_leave_model';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hrms_leave_type_id',
        'hrms_leave_rank_id',
        'year_of_service',
        'entitled_days',
        'carry_forward_days', // This is now interpreted as the MAX fixed carry-forward
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'year_of_service' => 'integer',
        'entitled_days' => 'integer', // Assuming days are usually integers, adjust if float is common
        'carry_forward_days' => 'float', // Keep as float for potential half-days or specific policies
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
     * Get the leave type that owns the leave model.
     */
    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(HrmsLeaveType::class, 'hrms_leave_type_id');
    }

    /**
     * Get the leave rank that owns the leave model.
     */
    public function leaveRank(): BelongsTo
    {
        return $this->belongsTo(HrmsLeaveRank::class, 'hrms_leave_rank_id');
    }
}
