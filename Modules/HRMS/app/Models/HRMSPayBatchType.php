<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\HRMS\Database\Factories\HRMSPayBatchTypeFactory;

class HRMSPayBatchType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'hrms_pay_batch_type';
    
    protected $fillable = [
        'name',
        'is_active',
    ];
}
