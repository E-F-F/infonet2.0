<?php

namespace Modules\IMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\IMS\Database\Factories\IMSSupplierFactory;

class IMSSupplier extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = "ims_supplier";
    
    protected $fillable = [
        'supplier_name',
        'supplier_office_number',
        'supplier_email',
        'supplier_address',
        'supplier_name',
        'supplier_name',
        'supplier_name',
        'supplier_name',
    ];
}
