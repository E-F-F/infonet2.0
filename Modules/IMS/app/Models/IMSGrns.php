<?php

namespace Modules\IMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\IMS\Database\Factories\IMSGrnsFactory;

class IMSGrns extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = "ims_grns";

    protected $fillable = [
        'purchase_order_id',
        'grn_number',
        'grn_date',
        'received_by',
    ];

    public function items()
    {
        return $this->hasMany(IMSGrnItems::class, 'grn_id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(IMSPurchaseOrders::class, 'purchase_order_id');
    }
}
