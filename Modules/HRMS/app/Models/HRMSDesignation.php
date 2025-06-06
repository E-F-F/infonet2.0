<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Modules\HRMS\Database\Factories\HRMSDesignationFactory;

class HRMSDesignation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hrms_designation';

    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $dates = ['deleted_at'];
}
