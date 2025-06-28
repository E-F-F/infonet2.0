<?php

namespace Modules\IMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\IMS\Database\Factories\IMSGrnItemsFactory;

class IMSGrnItems extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'ims_grn_items';

    protected $fillable = [
        'grn_id',
        'purchase_order_item_id',
        'quantity_received',
    ];

    public function grn()
    {
        return $this->belongsTo(IMSGrns::class, 'grn_id');
    }

    public function purchaseOrderItem()
    {
        return $this->belongsTo(IMSPurchaseOrderItems::class, 'purchase_order_item_id');
    }
}
