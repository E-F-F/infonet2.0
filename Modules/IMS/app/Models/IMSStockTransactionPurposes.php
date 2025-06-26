<?php

namespace Modules\IMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\IMS\Database\Factories\IMSStockTransactionPurposesFactory;

class IMSStockTransactionPurposes extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = "ims_stock_transaction_purposes";

    protected $fillable = [
        'name',
    ];
}
