<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HRMSHoliday extends Model
{
    use HasFactory;

    protected $table = 'hrms_holiday';

    protected $fillable = [
        'name',
        'holiday_date',
        'effective_date',
        'type',
        'status',
    ];
}
