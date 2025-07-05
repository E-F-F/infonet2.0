<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * SystemAccess Model
 *
 * This model represents the 'system_access' table, defining access levels for branches.
 */
class SystemAccess extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "system_access";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'access_name',
        'branch_id',
        'hrms', // Assuming 'hrms' is a boolean or string representing HRMS access
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'hrms' => 'boolean', // Assuming 'hrms' column stores boolean values
    ];

    /**
     * Get the branch that the system access belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /**
     * Get the staff access records associated with this system access.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function staffAccess(): HasMany
    {
        return $this->hasMany(StaffAccess::class, 'system_access_id');
    }
}
