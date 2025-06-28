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

    protected $casts = [
        'weight' => 'integer',
        'default_purchase_cost' => 'decimal:4',
        'default_sales_price' => 'decimal:4',
    ];

    // Relationships

    public function stock()
    {
        return $this->belongsTo(IMSStock::class, 'ims_stock_id');
    }

    public function batches()
    {
        return $this->hasMany(IMSStockBatch::class, 'ims_stock_variant_id');
    }

    // 🔹 Optional: computed attribute for quick display
    public function getVariantLabelAttribute()
    {
        return collect([$this->make, $this->size, $this->color, $this->brand])
            ->filter()
            ->implode(' / ');
    }

    // 🔹 Optional: scope for filtering by size/color/make
    public function scopeMatchAttributes($query, array $attributes)
    {
        return $query->when($attributes['size'] ?? null, fn($q) => $q->where('size', $attributes['size']))
            ->when($attributes['color'] ?? null, fn($q) => $q->where('color', $attributes['color']))
            ->when($attributes['make'] ?? null, fn($q) => $q->where('make', $attributes['make']));
    }
}
