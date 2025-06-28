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

    protected $casts = [
        'expiry_date' => 'date',
        'purchase_cost' => 'decimal:4',
        'sales_price' => 'decimal:4',
    ];

    // Relationships

    public function variant()
    {
        return $this->belongsTo(IMSStockVariant::class, 'ims_stock_variant_id');
    }

    public function supplier()
    {
        return $this->belongsTo(IMSSupplier::class, 'ims_supplier_id');
    }

    public function quantities()
    {
        return $this->hasMany(IMSStockQuantity::class, 'ims_stock_batch_id');
    }
}
