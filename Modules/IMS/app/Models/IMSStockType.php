<?php

namespace Modules\IMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\IMS\Database\Factories\IMSStockTypeFactory;

class IMSStockType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'ims_stock_type';

    protected $fillable = [
        'name',
    ];

    public function stocks()
    {
        return $this->hasMany(IMSStock::class, 'ims_stock_type_id');
    }
}
