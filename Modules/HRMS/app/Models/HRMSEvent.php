<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\HRMS\Database\Factories\HRMSEventFactory;

class HRMSEvent extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'hrms_event';

    protected $fillable = [
        'hrms_event_type_id',
        'title',
        'start_date',
        'end_date',
        'event_company',
        'event_branch',
        'event_venue',
        'remarks',
        'activity_logs',
        'is_active',
    ];

    // protected static function newFactory(): HRMSEventFactory
    // {
    //     // return HRMSEventFactory::new();
    // }
}
