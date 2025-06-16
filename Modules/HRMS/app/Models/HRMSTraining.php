<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Branch;
// use Modules\HRMS\Database\Factories\HRMSTrainingFactory;

class HRMSTraining extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'hrms_training';
    protected $fillable = [
        'branch_id',
        'training_start_date',
        'training_end_date',
        'hrms_training_type_id',
        'training_name',
        'hrms_training_award_type_id',
    ];

    // Relationship

    public function trainingAwardType()
    {
        return $this->belongsTo(HRMSTrainingAwardType::class, 'hrms_training_award_type_id');
    }
    public function trainingType()
    {
        return $this->belongsTo(HRMSTrainingType::class, 'hrms_training_type_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
