<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SystemAccess;
use App\Models\StaffAuth;

class StaffAccess extends Model
{
    protected $table = 'staff_access';

    protected $fillable = [
        'staff_auth_id',
        'system_access_id'
    ];
    // Relationships
    public function systemAccess()
    {
        return $this->belongsTo(SystemAccess::class);
    }
    public function staffAuth()
    {
        return $this->belongsTo(StaffAuth::class);
    }
}
