<?php

namespace Modules\IMS\Models;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\IMS\Database\Factories\IMSStockQuantityFactory;

class IMSStockQuantity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'ims_stock_quantity';

    protected $fillable = [
        'ims_stock_batch_id',
        'branch_id',
        'rack_no',
        'shelf_no',
        'bin_no',
        'location_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    // Relationships

    public function stockBatch()
    {
        return $this->belongsTo(IMSStockBatch::class, 'ims_stock_batch_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function location()
    {
        return $this->belongsTo(Branch::class, 'location_id');
    }
}
