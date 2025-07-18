<?php

namespace Modules\IMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\HRMS\Models\HRMSStaff;

// use Modules\IMS\Database\Factories\IMSPurchaseOrdersFactory;

class IMSPurchaseOrders extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'ims_purchase_orders';

    protected $fillable = [
        'supplier_id',
        'tax_id',
        'expected_receipt_date',
        'billing_address',
        'shipping_address',
        'tracking_ref',
        'purchase_order_running_number',
        'purchase_order_notes',
        'purchase_internal_notes',
        'status',
        'created_by',
        'updated_by',
        'received_by',
        'approved_by',
        'created_at',
        'updated_at',
        'received_at',
        'approved_at',
    ];

    public function items()
    {
        return $this->hasMany(IMSPurchaseOrderItems::class, 'purchase_order_id');
    }

    public function grns()
    {
        return $this->hasMany(IMSGrns::class, 'purchase_order_id');
    }

    public function supplier()
    {
        return $this->belongsTo(IMSSupplier::class, 'supplier_id');
    }

    public function tax()
    {
        return $this->belongsTo(IMSTax::class, 'tax_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(HRMSStaff::class, 'created_by');
    }
}
