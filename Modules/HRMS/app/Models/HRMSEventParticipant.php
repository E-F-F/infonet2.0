<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use DateTimeInterface;

class HRMSEventParticipant extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hrms_event_participant';

    protected $fillable = [
        'hrms_event_id',
        'hrms_staff_id',
    ];

    protected $dates = ['deleted_at'];

    /**
     * Belongs to Event
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(HRMSEvent::class, 'hrms_event_id');
    }

    /**
     * Belongs to Staff
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(HRMSStaff::class, 'hrms_staff_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
