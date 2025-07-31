<?php

namespace Modules\CRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CRMSBusinessNature extends Model
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
    protected $table = 'crms_business_nature';

    public function people()
    {
        return $this->hasMany(CRMSPeople::class, 'crms_business_nature_id');
    }
}
