<?php

namespace Modules\IMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\IMS\Database\Factories\IMSStockTransactionItemsFactory;

class IMSStockTransactionItems extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = "ims_stock_transaction_items";

    protected $fillable = [
        'ims_stock_transaction_id',
        'ims_purchase_order_id',
        'ims_stock_variant_id',
        'status',
        'quantity',
    ];

    public function transaction()
    {
        return $this->belongsTo(IMSStockTransactions::class, 'ims_stock_transaction_id');
    }

    public function variant()
    {
        return $this->belongsTo(IMSStockVariant::class, 'ims_stock_variant_id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(IMSPurchaseOrders::class, 'ims_purchase_order_id');
    }
}