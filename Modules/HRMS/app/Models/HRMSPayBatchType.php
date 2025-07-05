<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Modules\HRMS\Database\Factories\HRMSPayBatchTypeFactory;

/**
 * HRMSPayBatchType Model
 *
 * This model represents the 'hrms_pay_batch_type' table.
 * It supports soft deletes.
 */
class HRMSPayBatchType extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hrms_pay_batch_type';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
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
     * Get the payroll records associated with this pay batch type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payrolls(): HasMany
    {
        return $this->hasMany(HRMSPayroll::class, 'hrms_pay_batch_type_id');
    }
}