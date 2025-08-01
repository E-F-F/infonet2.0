<?php

namespace Modules\CRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\CRMS\Database\Factories\CRMSPeopleRaceFactory;

class CRMSPeopleVisitingRecord extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'crms_people_marketing_info_id',
    ];

    /**
     * The table associated with the model.
     */
    protected $table = 'crms_people_visiting_record';

    // public function people()
    // {
    //     return $this->hasMany(CRMSPeople::class, 'crms_people_race_id');
    // }
}
