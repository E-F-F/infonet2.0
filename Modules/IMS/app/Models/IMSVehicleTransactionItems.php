<?php

namespace Modules\IMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\IMS\Database\Factories\IMSVehicleTransactionItemsFactory;

class IMSVehicleTransactionItems extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'ims_vehicle_transaction_items';

    protected $fillable = [
        'ims_stock_transaction_id',
        'ims_stock_vehicle_id',
        'status',
    ];

    public function transaction()
    {
        return $this->belongsTo(IMSStockTransactions::class, 'ims_stock_transaction_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(IMSStockVehicles::class, 'ims_stock_vehicle_id');
    }
}
