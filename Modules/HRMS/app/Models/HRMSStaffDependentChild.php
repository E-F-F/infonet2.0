<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import BelongsTo for the relationship

/**
 * HRMSStaffDependentChild Model
 *
 * This model represents the 'hrms_staff_dependent_child' table, storing details of staff's children.
 * It has a belongsTo relationship with HRMSStaffPersonal.
 */
class HRMSStaffDependentChild extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hrms_staff_dependent_child';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hrms_staff_personal_id',
        'name',
        'dob',
        'remark',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'dob' => 'date',
    ];

    /**
     * Get the personal staff record that owns the dependent child.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function personal(): BelongsTo
    {
        return $this->belongsTo(HRMSStaffPersonal::class, 'hrms_staff_personal_id');
    }
}
