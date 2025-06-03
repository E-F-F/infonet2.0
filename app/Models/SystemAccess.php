<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemAccess extends Model
{
    protected $table = "system_access";
    protected $fillable = [
        'branch_id',
        'hrms'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
