<?php

namespace Modules\IMS\Models;

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
        'ims_vehicle_id',
    ];
}
