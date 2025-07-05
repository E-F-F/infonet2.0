<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SystemAccess;
use App\Models\StaffAuth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * StaffAccess Model
 *
 * This model represents the 'staff_access' pivot table, linking staff authentication
 * to system access permissions.
 */
class StaffAccess extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'staff_access';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'staff_auth_id',
        'system_access_id',
    ];

    /**
     * Get the system access record that the staff access belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function systemAccess(): BelongsTo
    {
        return $this->belongsTo(SystemAccess::class, 'system_access_id');
    }

    /**
     * Get the staff authentication record that the staff access belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function staffAuth(): BelongsTo
    {
        return $this->belongsTo(StaffAuth::class, 'staff_auth_id');
    }
}
