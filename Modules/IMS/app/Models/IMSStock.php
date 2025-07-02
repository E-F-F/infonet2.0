<?php

namespace Modules\IMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class IMSStock extends Model
{
    use HasFactory, SoftDeletes;

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

    protected $casts = [
        'activity_logs' => 'array',
        'stock_stable_unit' => 'integer',
    ];

    // Relationships
    public function stockCategory()
    {
        return $this->belongsTo(IMSStockCategory::class, 'ims_stock_category_id');
    }

    public function stockType()
    {
        return $this->belongsTo(IMSStockType::class, 'ims_stock_type_id');
    }

    public function variants()
    {
        return $this->hasMany(IMSStockVariant::class, 'ims_stock_id');
    }

    public function vehicles()
    {
        return $this->hasMany(IMSStockVehicles::class, 'ims_stock_id');
    }
}
