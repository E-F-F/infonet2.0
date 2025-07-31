<?php

namespace Modules\CRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\CRMS\Database\Factories\CRMSPeopleOccupationFactory;

class CRMSPeopleOccupation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'is_active',
    ];

    /**
     * The table associated with the model.
     */
    protected $table = 'crms_people_occupation';

    public function people()
    {
        return $this->hasMany(CRMSPeople::class, 'crms_people_occupation_id');
    }
}
