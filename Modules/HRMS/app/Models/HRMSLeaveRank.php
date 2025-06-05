<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\HRMS\Database\Factories\HRMSLeaveRankFactory;

class HRMSLeaveRank extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'hrms_leave_rank';
    protected $fillable = [
        'name',
        'is_active'
    ];

    // protected static function newFactory(): HRMSLeaveRankFactory
    // {
    //     // return HRMSLeaveRankFactory::new();
    // }
}
