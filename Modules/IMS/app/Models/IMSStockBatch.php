<?php

namespace Modules\IMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\IMS\Database\Factories\IMSStockBatchFactory;

class IMSStockBatch extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'ims_stock_batch';

    protected $fillable = [
        'ims_stock_variant_id',
        'ims_supplier_id',
        'batch_no',
        'expiry_date',
        'purchase_cost',
        'sales_price',
    ];
}
