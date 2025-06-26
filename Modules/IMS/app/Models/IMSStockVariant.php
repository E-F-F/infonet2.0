<?php

namespace Modules\IMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\IMS\Database\Factories\IMSStockVariantFactory;

class IMSStockVariant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'ims_stock_variant';
    
    protected $fillable = [
        'ims_stock_id',
        'sku_code',
        'size',
        'color',
        'make',
        'brand',
        'weight',
        'default_purchase_cost',
        'default_sales_price',
    ];
}
