<?php

namespace Modules\CRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CRMSPeopleMarketingInfo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'crms_people_id',
        'other_related_business',
        'business_current_future',
        'repurchase_timing',
    ];

    /**
     * The table associated with the model.
     */
    protected $table = 'crms_people_marketing_info';
}
