<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\HRMS\Database\Factories\HRMSOffenceFactory;

class HRMSOffence extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'hrms_offence';

    protected $fillable = [
        'branch_id',
        'hrms_staff_id',
        'issue_date',
        'hrms_offence_type_id',
        'description',
        'action_taken',
        'created_by',
        'updated_by',
    ];
}
