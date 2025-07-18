<?php

namespace Modules\IMS\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\IMS\Models\IMSPurchaseOrders;
use Modules\IMS\Models\IMSPurchaseOrderItems;
use Modules\IMS\Models\IMSGrn;
use Modules\IMS\Models\IMSGrnItem;
use Modules\IMS\Models\IMSGrnItems;
use Modules\IMS\Models\IMSGrns;
use Modules\IMS\Models\IMSStockBatch;
use Modules\IMS\Models\IMSStockQuantity;

class IMSStockMarketingGRNController extends Controller
{
    /**
     * Display a listing of GRNs.
     */
    public function index()
    {
        $grns = IMSGrns::with(['purchaseOrder.supplier', 'purchaseOrder.tax', 'items.purchaseOrderItem.variant.stock'])
            ->latest()
            ->get();

        return response()->json($grns->map(function ($grn) {
            return [
                'id' => $grn->id,
                'grn_number' => $grn->grn_number,
                'grn_date' => $grn->grn_date,
                'purchase_order_id' => $grn->purchase_order_id,
                'purchase_order_running_number' => $grn->purchaseOrder->purchase_order_running_number ?? null,
                'supplier_name' => $grn->purchaseOrder->supplier->supplier_name ?? null,
                'tax_name' => $grn->purchaseOrder->tax->tax_name ?? null,
                'received_by' => $grn->received_by,
                'created_at' => $grn->created_at,
                'updated_at' => $grn->updated_at,
                'items' => $grn->grnItems->map(function ($item) {
                    return [
                        'purchase_order_item_id' => $item->purchase_order_item_id,
                        'variant_id' => $item->purchaseOrderItem->ims_stock_variant_id,
                        'stock_name' => $item->purchaseOrderItem->variant->stock->name ?? null,
                        'quantity_received' => $item->quantity_received,
                    ];
                }),
            ];
        }));
    }

    /**
     * Return data for creating a new GRN.
     */
    public function create()
    {
        $purchaseOrders = IMSPurchaseOrders::with(['supplier', 'tax', 'items.variant.stock'])
            ->where('status', 'submitted')
            ->get()
            ->map(function ($po) {
                return [
                    'id' => $po->id,
                    'purchase_order_running_number' => $po->purchase_order_running_number,
                    'supplier_name' => $po->supplier->supplier_name ?? null,
                    'tax_name' => $po->tax->tax_name ?? null,
                    'items' => $po->items->map(function ($item) {
                        return [
                            'purchase_order_item_id' => $item->id,
                            'variant_id' => $item->ims_stock_variant_id,
                            'stock_name' => $item->variant->stock->name ?? null,
                            'quantity_ordered' => $item->quantity_ordered,
                            'quantity_received' => $item->quantity_received,
                            'unit_cost' => $item->unit_cost,
                        ];
                    }),
                ];
            });

        return response()->json([
            'purchase_orders' => $purchaseOrders,
        ]);
    }

    /**
     * Store a newly created GRN in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'purchase_order_id' => 'required|exists:ims_purchase_orders,id',
            'grn_number' => 'required|string|unique:ims_grns,grn_number',
            'grn_date' => 'required|date',
            'branch_id' => 'required|exists:branch,id',
            'items' => 'required|array|min:1',
            'items.*.purchase_order_item_id' => 'required|exists:ims_purchase_order_items,id',
            'items.*.quantity_received' => 'required|integer|min:1',
            'items.*.batch_no' => 'nullable|string',
            'items.*.expiry_date' => 'nullable|date',
            'items.*.bin_no' => 'required|string',
            'items.*.rack_no' => 'nullable|string',
            'items.*.shelf_no' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Verify purchase order is in 'submitted' status
            $po = IMSPurchaseOrders::where('id', $validated['purchase_order_id'])
                ->where('status', 'submitted')
                ->firstOrFail();

            // Create GRN
            $grn = IMSGrns::create([
                'purchase_order_id' => $validated['purchase_order_id'],
                'grn_number' => $validated['grn_number'],
                'grn_date' => $validated['grn_date'],
                'received_by' => Auth::user()?->staff_id,
            ]);

            // Process each GRN item
            foreach ($validated['items'] as $item) {
                $poItem = IMSPurchaseOrderItems::findOrFail($item['purchase_order_item_id']);

                // Ensure the purchase order item belongs to the specified PO
                if ($poItem->purchase_order_id !== $po->id) {
                    throw new \Exception("Purchase order item {$poItem->id} does not belong to PO {$po->id}.");
                }

                // Check if quantity_received exceeds quantity_ordered
                $totalReceived = $poItem->quantity_received + $item['quantity_received'];
                if ($totalReceived > $poItem->quantity_ordered) {
                    throw new \Exception("Received quantity for item {$poItem->id} exceeds ordered quantity.");
                }

                // Create GRN item
                IMSGrnItems::create([
                    'grn_id' => $grn->id,
                    'purchase_order_item_id' => $poItem->id,
                    'quantity_received' => $item['quantity_received'],
                ]);

                // Update purchase order item
                $poItem->quantity_received = $totalReceived;
                $poItem->save();

                // Create or update stock batch
                $batch = IMSStockBatch::firstOrCreate(
                    [
                        'ims_stock_variant_id' => $poItem->ims_stock_variant_id,
                        'batch_no' => $item['batch_no'] ?? 'BATCH-' . uniqid(),
                        'ims_supplier_id' => $po->supplier_id,
                    ],
                    [
                        'expiry_date' => $item['expiry_date'] ?? null,
                        'purchase_cost' => $poItem->unit_cost,
                        'sales_price' => $poItem->variant->default_sales_price ?? $poItem->unit_cost * 1.2, // Example markup
                    ]
                );

                // Update stock quantity
                $stockQuantity = IMSStockQuantity::firstOrCreate(
                    [
                        'ims_stock_batch_id' => $batch->id,
                        'branch_id' => $validated['branch_id'],
                        'bin_no' => $item['bin_no'],
                    ],
                    [
                        'quantity' => 0,
                        'rack_no' => $item['rack_no'] ?? null,
                        'shelf_no' => $item['shelf_no'] ?? null,
                        'location_id' => $validated['branch_id'],
                    ]
                );

                $stockQuantity->quantity += $item['quantity_received'];
                $stockQuantity->save();
            }

            // Update PO status if fully received
            $allItemsReceived = $po->items->every(function ($item) {
                return $item->quantity_received >= $item->quantity_ordered;
            });

            if ($allItemsReceived) {
                $po->update([
                    'status' => 'received',
                    'received_by' => Auth::user()?->staff_id,
                    'received_at' => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'GRN created successfully.',
                'grn_id' => $grn->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to create GRN: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Show the specified GRN.
     */
    public function show($id)
    {
        $grn = IMSGrns::with([
            'purchaseOrder.supplier',
            'purchaseOrder tax',
            'grnItems.purchaseOrderItem.variant.stock',
        ])->findOrFail($id);

        return response()->json([
            'id' => $grn->id,
            'grn_number' => $grn->grn_number,
            'grn_date' => $grn->grn_date,
            'purchase_order_id' => $grn->purchase_order_id,
            'purchase_order_running_number' => $grn->purchaseOrder->purchase_order_running_number ?? null,
            'supplier_name' => $grn->purchaseOrder->supplier->supplier_name ?? null,
            'tax_name' => $grn->purchaseOrder->tax->tax_name ?? null,
            'received_by' => $grn->received_by,
            'created_at' => $grn->created_at,
            'updated_at' => $grn->updated_at,
            'items' => $grn->grnItems->map(function ($item) {
                return [
                    'purchase_order_item_id' => $item->purchase_order_item_id,
                    'variant_id' => $item->purchaseOrderItem->ims_stock_variant_id,
                    'stock_name' => $item->purchaseOrderItem->variant->stock->name ?? null,
                    'quantity_received' => $item->quantity_received,
                    'unit_cost' => $item->purchaseOrderItem->unit_cost,
                ];
            }),
        ]);
    }

    /**
     * Return data for editing a GRN.
     */
    public function edit($id)
    {
        $grn = IMSGrns::with([
            'purchaseOrder.supplier',
            'purchaseOrder.tax',
            'grnItems.purchaseOrderItem.variant.stock',
        ])->findOrFail($id);

        return response()->json([
            'id' => $grn->id,
            'grn_number' => $grn->grn_number,
            'grn_date' => $grn->grn_date,
            'purchase_order_id' => $grn->purchase_order_id,
            'purchase_order_running_number' => $grn->purchaseOrder->purchase_order_running_number ?? null,
            'branch_id' => $grn->grnItems->first()->purchaseOrderItem->stockQuantity->branch_id ?? null,
            'items' => $grn->grnItems->map(function ($item) {
                $stockQuantity = IMSStockQuantity::where('ims_stock_batch_id', $item->purchaseOrderItem->stockBatch->id)->first();
                return [
                    'purchase_order_item_id' => $item->purchase_order_item_id,
                    'variant_id' => $item->purchaseOrderItem->ims_stock_variant_id,
                    'stock_name' => $item->purchaseOrderItem->variant->stock->name ?? null,
                    'quantity_received' => $item->quantity_received,
                    'batch_no' => $item->purchaseOrderItem->stockBatch->batch_no ?? null,
                    'expiry_date' => $item->purchaseOrderItem->stockBatch->expiry_date ?? null,
                    'bin_no' => $stockQuantity->bin_no ?? null,
                    'rack_no' => $stockQuantity->rack_no ?? null,
                    'shelf_no' => $stockQuantity->shelf_no ?? null,
                ];
            }),
        ]);
    }

    /**
     * Update the specified GRN inexpect in storage.
     */
    public function update(Request $request, $id)
    {
        $grn = IMSGrns::findOrFail($id);

        $validated = $request->validate([
            'grn_number' => 'required|string|unique:ims_grns,grn_number,' . $id,
            'grn_date' => 'required|date',
            'branch_id' => 'required|exists:branch,id',
            'items' => 'required|array|min:1',
            'items.*.purchase_order_item_id' => 'required|exists:ims_purchase_order_items,id',
            'items.*.quantity_received' => 'required|integer|min:1',
            'items.*.batch_no' => 'nullable|string',
            'items.*.expiry_date' => 'nullable|date',
            'items.*.bin_no' => 'required|string',
            'items.*.rack_no' => 'nullable|string',
            'items.*.shelf_no' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Get existing GRN items to adjust stock quantities
            $existingItems = IMSGrnItems::where('grn_id', $grn->id)->get()->keyBy('purchase_order_item_id');

            // Update GRN
            $grn->update([
                'grn_number' => $validated['grn_number'],
                'grn_date' => $validated['grn_date'],
                'received_by' => Auth::user()?->staff_id,
            ]);

            // Process each updated item
            foreach ($validated['items'] as $item) {
                $poItem = IMSPurchaseOrderItems::findOrFail($item['purchase_order_item_id']);
                if ($poItem->purchase_order_id !== $grn->purchase_order_id) {
                    throw new \Exception("Purchase order item {$poItem->id} does not belong to PO {$grn->purchase_order_id}.");
                }

                // Calculate previous received quantity
                $previousQuantity = $existingItems[$item['purchase_order_item_id']]->quantity_received ?? 0;
                $newQuantity = $item['quantity_received'];

                // Check if new total exceeds ordered quantity
                $totalReceived = ($poItem->quantity_received - $previousQuantity) + $newQuantity;
                if ($totalReceived > $poItem->quantity_ordered) {
                    throw new \Exception("Received quantity for item {$poItem->id} exceeds ordered quantity.");
                }

                // Update or create GRN item
                $grnItem = IMSGrnItems::updateOrCreate(
                    [
                        'grn_id' => $grn->id,
                        'purchase_order_item_id' => $item['purchase_order_item_id'],
                    ],
                    [
                        'quantity_received' => $newQuantity,
                    ]
                );

                // Update purchase order item
                $poItem->quantity_received = $totalReceived;
                $poItem->save();

                // Update or create stock batch
                $batch = IMSStockBatch::firstOrCreate(
                    [
                        'ims_stock_variant_id' => $poItem->ims_stock_variant_id,
                        'batch_no' => $item['batch_no'] ?? 'BATCH-' . uniqid(),
                        'ims_supplier_id' => $poItem->purchaseOrder->supplier_id,
                    ],
                    [
                        'expiry_date' => $item['expiry_date'] ?? null,
                        'purchase_cost' => $poItem->unit_cost,
                        'sales_price' => $poItem->variant->default_sales_price ?? $poItem->unit_cost * 1.2,
                    ]
                );

                // Update stock quantity
                $stockQuantity = IMSStockQuantity::firstOrCreate(
                    [
                        'ims_stock_batch_id' => $batch->id,
                        'branch_id' => $validated['branch_id'],
                        'bin_no' => $item['bin_no'],
                    ],
                    [
                        'quantity' => 0,
                        'rack_no' => $item['ack_no'] ?? null,
                        'shelf_no' => $item['shelf_no'] ?? null,
                        'location_id' => $validated['branch_id'],
                    ]
                );

                // Adjust stock quantity (subtract previous, add new)
                $stockQuantity->quantity = ($stockQuantity->quantity - $previousQuantity) + $newQuantity;
                $stockQuantity->save();
            }

            // Update PO status
            $po = IMSPurchaseOrders::findOrFail($grn->purchase_order_id);
            $allItemsReceived = $po->items->every(function ($item) {
                return $item->quantity_received >= $item->quantity_ordered;
            });

            if ($allItemsReceived) {
                $po->update([
                    'status' => 'received',
                    'received_by' => Auth::user()?->staff_id,
                    'received_at' => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'GRN updated successfully.',
                'grn_id' => $grn->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to update GRN: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Remove the specified GRN from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $grn = IMSGrns::findOrFail($id);

            // Reverse stock quantities for each GRN item
            foreach ($grn->grnItems as $grnItem) {
                $poItem = $grnItem->purchaseOrderItem;
                $batch = IMSStockBatch::where('ims_stock_variant_id', $poItem->ims_stock_variant_id)->first();

                if ($batch) {
                    $stockQuantity = IMSStockQuantity::where('ims_stock_batch_id', $batch->id)->first();
                    if ($stockQuantity) {
                        $stockQuantity->quantity -= $grnItem->quantity_received;
                        if ($stockQuantity->quantity <= 0) {
                            $stockQuantity->delete();
                        } else {
                            $stockQuantity->save();
                        }
                    }
                }

                // Reverse purchase order item quantity
                $poItem->quantity_received -= $grnItem->quantity_received;
                $poItem->save();
            }

            // Delete GRN and its items (cascade delete via migration)
            $grn->delete();

            // Update PO status
            $po = IMSPurchaseOrders::findOrFail($grn->purchase_order_id);
            $po->update([
                'status' => 'submitted',
                'received_by' => null,
                'received_at' => null,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'GRN deleted successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to delete GRN: ' . $e->getMessage(),
            ], 422);
        }
    }
}
