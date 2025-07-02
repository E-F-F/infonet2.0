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

    public function showStock()
    {
        $branchId = 1;

        $stocks = IMSStock::with([
            'stockType',
            'stockCategory',
            'variants.batches.quantities' => fn($q) => $q->where('branch_id', $branchId)
        ])->get();

        $data = $stocks->map(function ($stock) {
            return [
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
            ];
        });

        return response()->json($data);
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'running_number' => 'required|string', // ✅ Add this line
            'stock_category_id' => 'nullable|exists:ims_stock_category,id',
            'stock_type_id' => 'nullable|exists:ims_stock_type,id',
            'stock_department' => 'required|in:marketing,sparepart,vehicle',
            'unit_measure' => 'nullable|string',

            'variant.size' => 'nullable|string',
            'variant.color' => 'nullable|string',
            'variant.make' => 'nullable|string',
            'variant.brand' => 'nullable|string',
            'variant.weight' => 'nullable|numeric',
            'variant.default_purchase_cost' => 'nullable|numeric',
            'variant.default_sales_price' => 'nullable|numeric',
        ]);

        $stock = IMSStock::where('running_number', $validated['running_number'])->first();

        if (!$stock) {
            $stock = IMSStock::create([
                'name' => $validated['name'],
                'running_number' => $validated['running_number'],
                'ims_stock_category_id' => $validated['stock_category_id'] ?? null,
                'ims_stock_type_id' => $validated['stock_type_id'] ?? null,
                'stock_department' => $validated['stock_department'],
                'unit_measure' => $validated['unit_measure'] ?? null,
            ]);
        }

        // 2. Prepare variant data
        $variantData = $validated['variant'] ?? [];

        // 3. Add ims_stock_id to variant for unique check
        $variantWithStock = array_merge($variantData, ['ims_stock_id' => $stock->id]);

        // 4. Check or create
        $variant = IMSStockVariant::firstOrCreate(
            [
                'ims_stock_id' => $stock->id,
                'size' => $variantData['size'] ?? null,
                'color' => $variantData['color'] ?? null,
                'make' => $variantData['make'] ?? null,
                'weight' => $variantData['weight'] ?? null,
            ],
            [ // only fills on create
                'brand' => $variantData['brand'] ?? null,
                'default_purchase_cost' => $variantData['default_purchase_cost'] ?? null,
                'default_sales_price' => $variantData['default_sales_price'] ?? null,
            ]
        );

        return response()->json([
            'message' => 'Stock created successfully.',
            'data' => [
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
            ],
        ]);
    }



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
    public function update(Request $request, $variantId)
    {
        $validated = $request->validate([
            'size'               => 'nullable|string|max:100',
            'color'              => 'nullable|string|max:100',
            'make'               => 'nullable|string|max:100',
            'brand'              => 'nullable|string|max:100',
            'default_purchase_cost' => 'nullable|numeric',
            'default_sales_price'   => 'nullable|numeric',

            // Optional stock info updates (only if you're allowing them)
            'stock_name'         => 'nullable|string|max:255',
            'unit_measure'       => 'nullable|string|max:50',
        ]);

        $variant = IMSStockVariant::with('stock')->findOrFail($variantId);

        // Update variant fields
        $variant->update([
            'size'                 => $validated['size'],
            'color'                => $validated['color'],
            'make'                 => $validated['make'],
            'brand'                => $validated['brand'],
            'default_purchase_cost' => $validated['default_purchase_cost'],
            'default_sales_price'   => $validated['default_sales_price'],
        ]);

        // Optionally update some stock fields
        if (isset($validated['stock_name']) || isset($validated['unit_measure'])) {
            $variant->stock->update([
                'name'          => $validated['stock_name'] ?? $variant->stock->name,
                'unit_measure'  => $validated['unit_measure'] ?? $variant->stock->unit_measure,
            ]);
        }

        return response()->json([
            'message' => 'Variant updated successfully.',
            'variant' => $variant->fresh('stock'),
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
