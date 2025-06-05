<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\HRMS\Database\Factories\HRMSOffenceTypeFactory;

class HRMSOffenceType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'hrms_offence_type';
    protected $fillable = [
        'name',
        'is_active',
    ];
}
