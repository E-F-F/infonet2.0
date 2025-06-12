<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\StaffAccess;
use Modules\HRMS\Models\HRMSStaff;

class StaffAuth extends Authenticatable
{
    use Notifiable;

    protected $table = "staff_auth";

    protected $fillable = [
        'username',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
    // Relationships

    public function staffAccess()
    {
        return $this->hasMany(StaffAccess::class, 'staff_auth_id');
    }

    public function staff()
    {
        return $this->hasOne(HRMSStaff::class, 'staff_auth_id');
    }

    // Accessor for full name
    public function getNameAttribute()
    {
        return $this->staff->personal->fullName ?? 'Unknown';
    }
}
