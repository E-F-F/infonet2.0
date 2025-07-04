<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
/**
 * HRMSTrainingParticipant Model
 *
 * This model represents the 'hrms_training_participant' pivot table.
 * It links staff members to training records.
 */
class HRMSTrainingParticipant extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hrms_training_participant';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false; // This is a pivot table, usually no timestamps

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hrms_staff_id',
        'hrms_training_id',
    ];

    /**
     * Get the staff member associated with this training participation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(HRMSStaff::class, 'hrms_staff_id');
    }

    /**
     * Get the training associated with this training participation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function training(): BelongsTo
    {
        return $this->belongsTo(HRMSTraining::class, 'hrms_training_id');
    }
}