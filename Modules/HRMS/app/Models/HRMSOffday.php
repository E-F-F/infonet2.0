<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HRMSOffday extends Model
{
    use HasFactory;

    protected $table = 'hrms_offday';

    protected $fillable = [
        'name',
        'holiday_date',
        'effective_date',
        'recurring_interval',
        'recurring_end_date',
        'holiday_type',
        'status',
    ];
}
