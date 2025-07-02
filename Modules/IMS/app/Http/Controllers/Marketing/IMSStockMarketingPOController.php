<?php

namespace Modules\IMS\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\IMS\Models\IMSPurchaseOrderItems;
use Modules\IMS\Models\IMSPurchaseOrders;
use Modules\Staff\Models\Staff;

class IMSStockMarketingPOController extends Controller
{
    public function index()
    {
        $purchaseOrders = IMSPurchaseOrders::with(['supplier', 'tax'])->latest()->get();
        return response()->json($purchaseOrders);
    }

    public function show($id)
    {
        $po = IMSPurchaseOrders::with([
            'supplier',
            'tax',
            'items.stockVariant.stock'
        ])->findOrFail($id);

        return response()->json($po);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:ims_supplier,id',
            'tax_id' => 'required|exists:ims_tax,id',
            'expected_receipt_date' => 'required|date',
            'billing_address' => 'required|string',
            'shipping_address' => 'required|string',
            'tracking_ref' => 'required|string|unique:ims_purchase_orders,tracking_ref',
            'purchase_order_running_number' => 'required|string|unique:ims_purchase_orders,purchase_order_running_number',
            'purchase_order_notes' => 'nullable|string',
            'purchase_internal_notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.ims_stock_variant_id' => 'required|exists:ims_stock_variant,id',
            'items.*.quantity_ordered' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $po = IMSPurchaseOrders::create([
                'supplier_id' => $validated['supplier_id'],
                'tax_id' => $validated['tax_id'],
                'expected_receipt_date' => $validated['expected_receipt_date'],
                'billing_address' => $validated['billing_address'],
                'shipping_address' => $validated['shipping_address'],
                'tracking_ref' => $validated['tracking_ref'],
                'purchase_order_running_number' => $validated['purchase_order_running_number'],
                'purchase_order_notes' => $validated['purchase_order_notes'] ?? null,
                'purchase_internal_notes' => $validated['purchase_internal_notes'] ?? null,
                'created_by' => Auth::user()?->staff_id,

            ]);

            foreach ($validated['items'] as $item) {
                IMSPurchaseOrderItems::create([
                    'purchase_order_id' => $po->id,
                    'ims_stock_variant_id' => $item['ims_stock_variant_id'],
                    'quantity_ordered' => $item['quantity_ordered'],
                    'unit_cost' => $item['unit_cost'],
                ]);
            }
        });

        return response()->json(['message' => 'Purchase Order created successfully']);
    }

    public function update(Request $request, $id)
    {
        $po = IMSPurchaseOrders::findOrFail($id);

        $validated = $request->validate([
            'expected_receipt_date' => 'nullable|date',
            'billing_address' => 'nullable|string',
            'shipping_address' => 'nullable|string',
            'purchase_order_notes' => 'nullable|string',
            'purchase_internal_notes' => 'nullable|string',
            'status' => 'nullable|in:draft,submitted,received,cancelled',
        ]);

        $po->update(array_merge($validated, [
            'updated_by' => Auth::user()?->staff_id,
        ]));

        return response()->json(['message' => 'Purchase Order updated successfully']);
    }
}
