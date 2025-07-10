<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Branch Model
 *
 * This model represents the 'branch' table.
 */
class Branch extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'branch';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'branch_of',
        'name',
        'code',
        'address',
        'print_name',
        'company_reg_no',
        'description',
        'work_minutes_per_day',
        'epf_employer_no',
        'contact_person_name',
        'contact_phone_no',
        'socso_employer_no',
        'lhdn_employer_no',
        'hrdp_no',
        'bank_account_no',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'work_minutes_per_day' => 'integer',
    ];

    /**
     * Get the company this branch belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'branch_of');
    }

    /**
     * Get the system access records for the branch.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function systemAccesses(): HasMany
    {
        return $this->hasMany(SystemAccess::class, 'branch_id');
    }
}
