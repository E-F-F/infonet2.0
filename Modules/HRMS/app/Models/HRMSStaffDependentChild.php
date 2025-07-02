<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
     * Get the staff personal record that owns the dependent child.
     */
    public function staffPersonal()
    {
        return $this->belongsTo(HRMSStaffPersonal::class, 'hrms_staff_personal_id');
    }

    // If you plan to use a factory, uncomment the following and create the factory file.
    // protected static function newFactory(): HRMSStaffDependentChildFactory
    // {
    //     return HRMSStaffDependentChildFactory::new();
    // }
}
