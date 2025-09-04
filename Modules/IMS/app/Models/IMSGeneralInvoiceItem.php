<?php

namespace Modules\IMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class IMSGeneralInvoiceItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "ims_general_invoice_item";

    protected $fillable = [
        'general_invoice_id',
        'stock_id',
        'quantity',
        'price_scheme',
        'unit_price',
        'discount',
        'tax_code',
        'amount',
        'created_by',
        'created_datetime',
    ];
}