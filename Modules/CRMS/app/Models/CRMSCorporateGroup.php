<?php

namespace Modules\CRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CRMSCorporateGroup extends Model
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
    protected $table = 'crms_corporate_group';

    public function people()
    {
        return $this->hasMany(CRMSPeople::class, 'crms_corporate_group_id');
    }
}
