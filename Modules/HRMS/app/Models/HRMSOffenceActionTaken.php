<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * HRMSOffenceType Model
 *
 * This model represents the 'hrms_offence_type' table.
 * It supports soft deletes.
 */
class HRMSOffenceActionTaken extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hrms_offence_action_taken';

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
     * Get the offence records associated with this offence type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function offences(): HasMany
    {
        return $this->hasMany(HRMSOffence::class, 'hrms_offence_action_taken_id');
    }
}
