<?php

namespace Modules\CRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CRMSPeopleQuotation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'crms_people_quotation';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'status',

    ];
}
