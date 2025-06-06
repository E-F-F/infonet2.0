<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Modules\HRMS\Database\Factories\HRMSLeaveRankFactory;

class HRMSLeaveRank extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hrms_leave_rank';

    protected $fillable = [
        'name',
        'is_active'
    ];

    // Optional: protect or allow fields
    protected $dates = ['deleted_at'];
}
