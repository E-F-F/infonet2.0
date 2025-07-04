<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\StaffAccess;
use Modules\HRMS\Models\HRMSStaff;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Sanctum\HasApiTokens;

/**
 * StaffAuth Model
 *
 * This model represents the 'staff_auth' table and handles staff authentication.
 * It extends Authenticatable for Laravel's authentication features.
 */
class StaffAuth extends Authenticatable
{
    use Notifiable, HasFactory, HasApiTokens; // Added HasFactory

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "staff_auth";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean', // Cast is_active to boolean
        ];
    }

    /**
     * Get the staff access records for the staff authentication.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function staffAccess(): HasMany
    {
        return $this->hasMany(StaffAccess::class, 'staff_auth_id');
    }

    /**
     * Get the HRMS staff record associated with this authentication.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function hrmsStaff(): HasOne // Renamed to hrmsStaff to avoid conflict with `staff()` accessor
    {
        return $this->hasOne(HRMSStaff::class, 'staff_auth_id');
    }

    /**
     * Accessor for full name from the related HRMSStaffPersonal model.
     *
     * @return string|null
     */
    public function getNameAttribute(): ?string
    {
        // Ensure the hrmsStaff relationship is loaded before accessing personal details
        return $this->hrmsStaff?->personal?->fullName ?? 'Unknown';
    }
}
