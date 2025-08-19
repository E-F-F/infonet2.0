<?php

namespace Modules\IMS\Models;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IMSStockVehicles extends Model
{
    use HasFactory;

    protected $table = 'ims_stock_vehicles';

    protected $fillable = [
        'ims_stock_id',
        'vin',
        'engine_no',
        'chassis_no',
        'year_make',
        'arrival_date',
        'plate_no',
        'type',
        'ims_vehicle_make_id',
        'ims_vehicle_model_id',
        'ims_vehicle_colour_id',
        'ims_vehicle_body_type_id',
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
        'sales_price'  => 'decimal:2',
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

    public function make()
    {
        return $this->belongsTo(IMSVehicleMake::class, 'ims_vehicle_make_id');
    }

    public function model()
    {
        return $this->belongsTo(IMSVehicleModel::class, 'ims_vehicle_model_id');
    }

    public function colour()
    {
        return $this->belongsTo(IMSVehicleColour::class, 'ims_vehicle_colour_id');
    }

    public function bodyType()
    {
        return $this->belongsTo(IMSVehicleBodyType::class, 'ims_vehicle_body_type_id');
    }
}
