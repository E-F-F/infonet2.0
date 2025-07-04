<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * HRMSTraining Model
 *
 * This model represents the 'hrms_training' table, storing training details.
 * It includes relationships to Branch, Training Type, Training Award Type, and participants.
 */
class HRMSTraining extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hrms_training';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'branch_id',
        'training_start_date',
        'training_end_date',
        'hrms_training_type_id',
        'training_name',
        'hrms_training_award_type_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'training_start_date' => 'date',
        'training_end_date' => 'date',
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
     * Get the type of training.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function trainingType(): BelongsTo
    {
        return $this->belongsTo(HRMSTrainingType::class, 'hrms_training_type_id');
    }

    /**
     * Get the award type for the training.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function trainingAwardType(): BelongsTo
    {
        return $this->belongsTo(HRMSTrainingAwardType::class, 'hrms_training_award_type_id');
    }

    /**
     * Get the participants for the training.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participants(): HasMany
    {
        return $this->hasMany(HRMSTrainingParticipant::class, 'hrms_training_id');
    }
}