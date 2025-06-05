<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\HRMS\Database\Factories\HRMSTrainingAwardTypeFactory;

class HRMSTrainingAwardType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'hrms_training_award_type';
    protected $fillable = [
        'name',
        'is_active'
    ];

    // protected static function newFactory(): HRMSTrainingAwardTypeFactory
    // {
    //     // return HRMSTrainingAwardTypeFactory::new();
    // }
}
