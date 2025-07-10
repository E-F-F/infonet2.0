<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes; // Import the SoftDeletes trait
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * HRMSDesignation Model
 *
 * This model represents the 'hrms_designation' table.
 * It supports soft deletes.
 */
class HRMSDepartment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hrms_department';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'is_active',
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
     * Get the staff employment records associated with this designation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function designations(): HasMany
    {
        return $this->hasMany(HRMSDesignation::class, 'hrms_department_id');
    }
}
