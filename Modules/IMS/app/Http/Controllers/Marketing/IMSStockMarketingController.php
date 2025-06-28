<?php

namespace Modules\IMS\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\IMS\Models\IMSStock;
use Modules\IMS\Transformers\Marketing\IMSMarketingStockResource;
use Modules\IMS\Models\IMSStockVariant;

class IMSStockMarketingController extends Controller
{
    public function index()
    {
        $branchId = 1;

        $grouped = collect();

        $stocks = IMSStock::with([
            'variants.batches.quantities' => fn($q) => $q->where('branch_id', $branchId)
        ])->get();

        foreach ($stocks as $stock) {
            foreach ($stock->variants as $variant) {
                $totalQuantity = 0;
                $firstQuantity = null;
                $firstBatch = null;

                foreach ($variant->batches as $batch) {
                    foreach ($batch->quantities as $quantity) {
                        $totalQuantity += $quantity->quantity;
                        $firstQuantity ??= $quantity;
                        $firstBatch ??= $batch;
                    }
                }

                if ($totalQuantity > 0) {
                    $grouped->push(new IMSMarketingStockResource((object) [
                        'stock'    => $stock,
                        'variant'  => $variant,
                        'batch'    => $firstBatch,
                        'quantity' => (object) [
                            'quantity'   => $totalQuantity,
                            'branch_id'  => $firstQuantity->branch_id ?? $branchId,
                            'bin_no'     => $firstQuantity->bin_no ?? '-',
                        ],
                    ]));
                }
            }
        }

        return response()->json($grouped);
    }

    public function show($variantId)
    {
        $branchId = 1;

        // Find the variant with its stock and quantities
        $variant = IMSStockVariant::with([
            'stock.stockType',
            'stock.stockCategory',
            'batches.quantities' => fn($q) => $q->where('branch_id', $branchId)
        ])->findOrFail($variantId);

        $stock = $variant->stock;

        $quantities = [];

        foreach ($variant->batches as $batch) {
            foreach ($batch->quantities as $quantity) {
                $quantities[] = [
                    'batch_no'      => $batch->batch_no,
                    'purchase_cost' => $batch->purchase_cost,
                    'sales_price'   => $batch->sales_price,
                    'quantity'      => $quantity->quantity,
                    'branch_id'     => $quantity->branch_id,
                    'bin_no'        => $quantity->bin_no,
                ];
            }
        }

        return response()->json([
            // Stock info
            'stock_id'           => $stock->id,
            'stock_name'         => $stock->name,
            'stock_running_no'   => $stock->running_number,
            'stock_type'         => $stock->stockType->name ?? null,
            'stock_category'     => $stock->stockCategory->name ?? null,
            'stock_department'   => $stock->stock_department,
            'stock_stable_unit'  => $stock->stock_stable_unit,
            'unit_measure'       => $stock->unit_measure,
            'image'              => $stock->image,
            'activity_logs'      => $stock->activity_logs,

            // Variant info
            'variant_id'         => $variant->id,
            'variant_size'       => $variant->size,
            'variant_color'      => $variant->color,
            'variant_make'       => $variant->make,
            'variant_brand'      => $variant->brand,

            // Quantity per batch
            'quantities'         => $quantities,
        ]);
    }


    public function showStockAllVariant($stockId)
    {
        $branchId = 1;

        $stock = IMSStock::with([
            'stockType',
            'stockCategory',
            'variants.batches.quantities' => fn($q) => $q->where('branch_id', $branchId)
        ])->findOrFail($stockId);

        $variants = [];

        foreach ($stock->variants as $variant) {
            $quantities = [];
            $totalQuantity = 0;

            foreach ($variant->batches as $batch) {
                foreach ($batch->quantities as $quantity) {
                    $totalQuantity += $quantity->quantity;

                    $quantities[] = [
                        'batch_no'      => $batch->batch_no,
                        'purchase_cost' => $batch->purchase_cost,
                        'sales_price'   => $batch->sales_price,
                        'quantity'      => $quantity->quantity,
                        'branch_id'     => $quantity->branch_id,
                        'bin_no'        => $quantity->bin_no,
                    ];
                }
            }

            // Only include if variant has quantities in this branch
            if (!empty($quantities)) {
                $variants[] = [
                    'variant_id'     => $variant->id,
                    'variant_size'   => $variant->size,
                    'variant_color'  => $variant->color,
                    'variant_make'   => $variant->make,
                    'variant_brand'  => $variant->brand,
                    'total_quantity' => $totalQuantity,
                    'quantities'     => $quantities,
                ];
            }
        }

        return response()->json([
            'stock_id'           => $stock->id,
            'stock_name'         => $stock->name,
            'stock_running_no'   => $stock->running_number,
            'stock_type'         => $stock->stockType->name ?? null,
            'stock_category'     => $stock->stockCategory->name ?? null,
            'stock_department'   => $stock->stock_department,
            'stock_stable_unit'  => $stock->stock_stable_unit,
            'unit_measure'       => $stock->unit_measure,
            'image'              => $stock->image,
            'activity_logs'      => $stock->activity_logs,

            'variants' => $variants,
        ]);
    }

    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('ims::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
