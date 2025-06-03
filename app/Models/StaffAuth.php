<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\StaffAccess;

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
    // Relationships

    public function staffAccess()
    {
        return $this->hasMany(StaffAccess::class, 'staff_auth_id');
    }
}
