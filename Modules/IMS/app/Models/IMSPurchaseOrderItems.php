<?php

namespace Modules\IMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\IMS\Database\Factories\IMSPurchaseOrderItemsFactory;

class IMSPurchaseOrderItems extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'ims_purchase_order_items';

    protected $fillable = [
        'purchase_order_id',
        'ims_stock_variant_id',
        'quantity_ordered',
        'quantity_received',
        'unit_cost',
    ];

    public function variant()
    {
        return $this->belongsTo(IMSStockVariant::class, 'ims_stock_variant_id');
    }

    public function grnItems()
    {
        return $this->hasMany(IMSGrnItems::class, 'purchase_order_item_id');
    }
}
