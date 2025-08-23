<?php

namespace Modules\CRMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\CRMS\Database\Factories\CRMSPeopleRaceFactory;

class CRMSPeopleBooking extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'crms_people_booking';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'status',
        'sa_remark',
        'status_date',
        'crms_people_quotation_id',
        'vso_no',
        'booking_date',
        'lap_date',
        'proposed_inv',
        'proposed_bank_loan_ammount',
        'proposed_tenure',
        'proposed_bank',
        'assigned_to',
    ];

    /**
     * Relationships Belongs To
     */
    public function quotation()
    {
        return $this->belongsTo(CRMSPeopleQuotation::class, 'crms_people_quotation_id');
    }
}
