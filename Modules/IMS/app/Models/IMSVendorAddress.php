<?php

namespace Modules\IMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class IMSVendorAddress extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "ims_vendor_address";

    protected $fillable = [
        'vendor_id',
        'address_type',
        'address',
        'postcode',
        'city',
        'state',
        'country',
        'contact',
        'telephone_number',
        'fax_number',
        'created_by',
        'created_datetime',
    ];
}