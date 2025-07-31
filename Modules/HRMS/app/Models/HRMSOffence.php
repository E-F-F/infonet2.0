<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Branch; // Assuming Branch model is in App\Models or similar
use DateTimeInterface;
/**
 * HRMSOffence Model
 *
 * This model represents the 'hrms_offence' table, storing staff offence records.
 * It includes relationships to Branch, HRMSStaff, HRMSOffenceType, and staff who tracked the record.
 * It also supports soft deletes.
 */
class HRMSOffence extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hrms_offence';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'branch_id',
        'hrms_staff_id',
        'issue_date',
        'hrms_offence_type_id',
        'description',
        'hrms_offence_action_taken_id',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'issue_date' => 'date',
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
     * Get the branch that the offence record belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /**
     * Get the staff member who committed the offence.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(HRMSStaff::class, 'hrms_staff_id');
    }

    /**
     * Get the type of offence.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function offenceType(): BelongsTo
    {
        return $this->belongsTo(HRMSOffenceType::class, 'hrms_offence_type_id');
    }
    
    public function actionTaken(): BelongsTo
    {
        return $this->belongsTo(HRMSOffenceActionTaken::class, 'hrms_offence_action_taken_id');
    }

    /**
     * Get the staff member who created this offence record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(HRMSStaff::class, 'created_by');
    }

    /**
     * Get the staff member who last updated this offence record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(HRMSStaff::class, 'updated_by');
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
