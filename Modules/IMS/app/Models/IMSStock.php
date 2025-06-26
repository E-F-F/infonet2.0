<?php

namespace Modules\IMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\IMS\Database\Factories\IMSStockFactory;

class IMSStock extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = "ims_stock";

    protected $fillable = [
        'name',
        'running_number',
        'description',
        'ims_stock_category_id',
        'ims_stock_type_id',
        'stock_department',
        'stock_stable_unit',
        'unit_measure',
        'image',
        'remark',
        'activity_logs',
    ];

    public function stockCategory()
    {
        return $this->belongsTo(IMSStockCategory::class, 'ims_stock_category_id');
    }

    public function stockType()
    {
        return $this->belongsTo(IMSStockType::class, 'ims_stock_type_id');
    }
}
