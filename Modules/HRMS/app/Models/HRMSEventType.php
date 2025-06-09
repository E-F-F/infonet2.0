<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Modules\HRMS\Database\Factories\HRMSEventTypeFactory;

class HRMSEventType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hrms_event_type';

    protected $fillable = [
        'name',
        'is_active'
    ];
}
