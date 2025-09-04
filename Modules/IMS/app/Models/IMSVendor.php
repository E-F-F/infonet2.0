<?php

namespace Modules\IMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class IMSVendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "ims_vendor";

    protected $fillable = [
        'code',
        'name',
        'company_name',
        'id_type',
        'id_number',                
        'tax_id_number',
        'tax_id_number_validated',
        'einvoice_start_date',
        'sst_reg_number',
        'tourism_tax_reg_number',
        'msic_code',
        'tax_person_type',
        'gst_reg_number',
        'gst_commence_date',
        'gst_last_verify_date',
        'bank',
        'bank_account_number',
        'bank_id_number',
        'email',
        'website',
        'vendor_group',
        'remarks',
        'status',
        'open_code',
        'auto_approve_po',
        'account',
        'terms',
        'use_foreign_currency',
        'created_by',
        'created_datetime',
    ];
}