<?php

namespace Modules\IMS\Transformers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IMSMarketingStockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            // Stock info
            'stock_id'           => $this->stock->id,
            'stock_name'         => $this->stock->name,
            'stock_running_no'   => $this->stock->running_number,
            'stock_type'         => $this->stock->stockType->name ?? null,
            'stock_category'     => $this->stock->stockCategory->name ?? null,
            'stock_department'   => $this->stock->stock_department,
            'stock_stable_unit'  => $this->stock->stock_stable_unit,
            'unit_measure'       => $this->stock->unit_measure,
            'image'              => $this->stock->image,
            'activity_logs'      => $this->stock->activity_logs,

            // Variant info
            'variant_id'         => $this->variant->id,
            'variant_size'       => $this->variant->size,
            'variant_color'      => $this->variant->color,
            'variant_make'       => $this->variant->make,
            'variant_brand'      => $this->variant->brand,

            // Quantity info (aggregated)
            'quantity'           => $this->quantity->quantity,
            'branch_id'          => $this->quantity->branch_id,
            // 'bin_no'             => $this->quantity->bin_no,
        ];
        // return [
        //     // "Stock",
        //     'stock_id'    => $this->stock->id,
        //     'stock_name'  => $this->stock->name,
        //     'stock_type'  => $this->stock->running_number,
        //     'stock_category' => $this->stock->stockCategory->name,
        //     'stock_type'  => $this->stock->stockType->name,
        //     'stock_department' => $this->stock->stock_department,
        //     'stock_stable_unit' => $this->stock->stock_stable_unit,
        //     'unit_measure' => $this->stock->unit_measure,
        //     'image' => $this->stock->image,
        //     'activity_logs' => $this->stock->activity_logs,
        //     // "StockVariant",
        //     'variant_id'  => $this->variant->id,
        //     // 'sku_code'    => $this->variant->sku_code, // Not Important for marketing
        //     'size'        => $this->variant->size,
        //     'color'       => $this->variant->color,
        //     'make'        => $this->variant->make,
        //     'brand'       => $this->variant->brand,

        //     'batch_id'    => $this->batch->id,
        //     'batch_no'    => $this->batch->batch_no,
        //     // 'expiry_date' => $this->batch->expiry_date, // Not Important for marketing
        //     'purchase_cost'=> $this->batch->purchase_cost,
        //     'sales_price'     => $this->batch->sales_price,

        //     'quantity'    => $this->quantity->quantity,
        //     'branch_id'   => $this->quantity->branch_id,
        //     'bin_no'      => $this->quantity->bin_no,
        // ];
    }
}
