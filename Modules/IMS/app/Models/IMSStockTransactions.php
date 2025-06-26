<?php

namespace Modules\IMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\IMS\Database\Factories\IMSStockTransactionsFactory;

class IMSStockTransactions extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = "ims_stock_transactions";

    protected $fillable = [
        'running_number',
        'ims_supplier_id',
        'type',
        'recipient_name',
        'ims_shipping_option_id',
        'status',
        'ims_stock_transaction_purpose_id',
        'attachment',
        'from_branch_id',
        'to_branch_id',
        'remark',
        'activity_log',
        'total_cost',
        'created_by',
        'updated_by',
        'received_by',
        'approved_by',
        'rejected_by',
        'created_at',
        'updated_at',
        'received_at',
        'approved_at',
        'rejected_at',
    ];
}