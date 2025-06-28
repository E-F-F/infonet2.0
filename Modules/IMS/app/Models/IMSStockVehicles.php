<?php

namespace Modules\IMS\Models;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\IMS\Database\Factories\IMSStockVehiclesFactory;

class IMSStockVehicles extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'ims_stock_vehicles';

    protected $fillable = [
        'ims_stock_id',
        'vin',
        'engine_no',
        'plate_no',
        'color',
        'year',
        'arrival_date',
        'supplier_id',
        'purchase_cost',
        'sales_price',
        'branch_id',
        'location_code',
        'status',
    ];

    protected $casts = [
        'arrival_date' => 'date',
        'purchase_cost' => 'decimal:2',
        'sales_price' => 'decimal:2',
    ];

    // Relationships
    public function stock()
    {
        return $this->belongsTo(IMSStock::class, 'ims_stock_id');
    }

    public function supplier()
    {
        return $this->belongsTo(IMSSupplier::class, 'supplier_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
