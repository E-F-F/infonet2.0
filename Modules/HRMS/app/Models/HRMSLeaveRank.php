<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes; // Import the SoftDeletes trait
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * HRMSLeaveRank Model
 *
 * This model represents the 'hrms_leave_rank' table.
 * It supports soft deletes.
 */
class HRMSLeaveRank extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hrms_leave_rank';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
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
     * Get the staff employment records associated with this leave rank.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function employmentRecords(): HasMany
    {
        return $this->hasMany(HRMSStaffEmployment::class, 'hrms_leave_rank_id');
    }
}