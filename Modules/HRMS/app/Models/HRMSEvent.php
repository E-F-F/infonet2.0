<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Branch;
use DateTimeInterface;
/**
 * HRMSEvent Model
 *
 * This model represents the 'hrms_event' table, storing event details.
 * It includes relationships to Event Type and handles activity logs.
 * It also supports soft deletes.
 */
class HRMSEvent extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hrms_event';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hrms_event_type_id',
        'title',
        'start_date',
        'end_date',
        'event_company',
        'branch_id',
        'event_venue',
        'remarks',
        'activity_logs',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'activity_logs' => 'array', // Cast JSON column to array
        'is_active' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array<int, string>
     */
    protected $dates = [
        'deleted_at',
    ];

    /**
     * Get the branch that the training belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /**
     * Get the event type that the event belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function eventType(): BelongsTo
    {
        return $this->belongsTo(HRMSEventType::class, 'hrms_event_type_id');
    }

    /**
     * Get the participants for the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participants()
    {
        return $this->hasMany(HRMSEventParticipant::class, 'hrms_event_id');
    }

    /**
     * Date.
     *
     * @return void
     */
    public function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d');
    }
}