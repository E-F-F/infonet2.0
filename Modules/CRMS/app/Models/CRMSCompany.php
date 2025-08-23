<?php

namespace Modules\CRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CRMSCompany extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_name',
        'sst_reg_no',
        'gst_reg_no',
        'company_size',
        'sector',
        'crms_business_nature_id'
    ];

    /**
     * The table associated with the model.
     */
    protected $table = 'crms_company';

    public function people()
    {
        return $this->hasMany(CRMSPeople::class, 'crms_company_id');
    }
}
