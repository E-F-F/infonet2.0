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
        $pos = IMSPurchaseOrders::with(['supplier', 'tax'])->latest()->get();

        return response()->json($pos->map(function ($po) {
            return [
                'supplier_id' => $po->supplier_id,
                'supplier_name' => $po->supplier->supplier_name ?? null,
                'tax_id' => $po->tax_id,
                'tax_name' => $po->tax->tax_name ?? null,
                'expected_receipt_date' => $po->expected_receipt_date,
                'billing_address' => $po->billing_address,
                'shipping_address' => $po->shipping_address,
                'tracking_ref' => $po->tracking_ref,
                'purchase_order_running_number' => $po->purchase_order_running_number,
                'purchase_order_notes' => $po->purchase_order_notes,
                'purchase_internal_notes' => $po->purchase_internal_notes,
                'status' => $po->status,
                'created_by' => $po->created_by,
                'updated_by' => $po->updated_by,
                'received_by' => $po->received_by,
                'approved_by' => $po->approved_by,
                'created_at' => $po->created_at,
                'updated_at' => $po->updated_at,
                'received_at' => $po->received_at,
                'approved_at' => $po->approved_at,
            ];
        }));
    }

    public function show($id)
    {
        $po = IMSPurchaseOrders::with([
            'supplier',
            'tax',
            'grns',
            'items.variant.stock'
        ])->findOrFail($id);

        return response()->json([
            // PO Info
            'supplier_id' => $po->supplier_id,
            'supplier_name' => $po->supplier->supplier_name ?? null,
            'tax_id' => $po->tax_id,
            'tax_name' => $po->tax->tax_name ?? null,
            'expected_receipt_date' => $po->expected_receipt_date,
            'billing_address' => $po->billing_address,
            'shipping_address' => $po->shipping_address,
            'tracking_ref' => $po->tracking_ref,
            'purchase_order_running_number' => $po->purchase_order_running_number,
            'purchase_order_notes' => $po->purchase_order_notes,
            'purchase_internal_notes' => $po->purchase_internal_notes,
            'status' => $po->status,
            'created_by' => $po->created_by,
            'updated_by' => $po->updated_by,
            'received_by' => $po->received_by,
            'approved_by' => $po->approved_by,
            'created_at' => $po->created_at,
            'updated_at' => $po->updated_at,
            'received_at' => $po->received_at,
            'approved_at' => $po->approved_at,

            // GRNs (optional: take first GRN if exists)
            'grns' => $po->grns->map(function ($grn) {
                return [
                    'grn_number' => $grn->grn_number,
                    'grn_date' => $grn->grn_date,
                    'received_by' => $grn->received_by,
                ];
            }),

            // Items
            'items' => $po->items->map(function ($item) {
                return [
                    'variant_id' => $item->ims_stock_variant_id,
                    'stock_name' => $item->variant->stock->name ?? null,
                    'stock_running_number' => $item->variant->stock->running_number ?? null,
                    'size' => $item->size,
                    'color' => $item->color,
                    'quantity_received' => $item->quantity_received,
                    'unit_cost' => $item->unit_cost,
                ];
            }),
        ]);
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

        return response()->json(['message' => 'Purchase Order created successfully.']);
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

        $po->update([
            'expected_receipt_date' => $validated['expected_receipt_date'] ?? $po->expected_receipt_date,
            'billing_address' => $validated['billing_address'] ?? $po->billing_address,
            'shipping_address' => $validated['shipping_address'] ?? $po->shipping_address,
            'purchase_order_notes' => $validated['purchase_order_notes'] ?? $po->purchase_order_notes,
            'purchase_internal_notes' => $validated['purchase_internal_notes'] ?? $po->purchase_internal_notes,
            'status' => $validated['status'] ?? $po->status,
            'updated_by' => Auth::user()?->staff_id,
        ]);

        return response()->json(['message' => 'Purchase Order updated successfully.']);
    }
}
