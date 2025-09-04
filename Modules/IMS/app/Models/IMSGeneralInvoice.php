<?php

namespace Modules\IMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class IMSGeneralInvoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "ims_general_invoice";

    protected $fillable = [
        'vendor_id',
        'branch_id',
        'service_advisor_id',
        'price_scheme',
        'shipping_method',
        'ship_to',
        'tax_exempt',
        'tax_doc_type',
        'general_invoice_number',
        'invoice_date',
        'invoice_due_date',
        'status',
        'reference_number',
        'customs_form_number',
        'title',
        'remarks',
        'total',
        'tax',
        'rounding',
        'grand_total',
        'created_by',
        'created_datetime',
    ];
}