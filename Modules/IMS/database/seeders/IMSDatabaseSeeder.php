<?php

namespace Modules\IMS\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\IMS\Models\IMSStock;
use Modules\IMS\Models\IMSStockBatch;
use Modules\IMS\Models\IMSStockQuantity;
use Modules\IMS\Models\IMSStockVariant;
use Modules\IMS\Models\IMSStockCategory; // Add this line
use Modules\IMS\Models\IMSStockType;    // Add this line
use Modules\IMS\Models\IMSSupplier;      // Add this line for suppliers, if it also causes issues

class IMSDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // First, ensure the parent records exist.
        // Create IMSStockCategory if it doesn't exist
        $category = IMSStockCategory::firstOrCreate(
            ['id' => 1], // Try to find by ID 1
            ['name' => 'Tools'] // If not found, create with this name
        );

        // Create IMSStockType if it doesn't exist
        $type = IMSStockType::firstOrCreate(
            ['id' => 1], // Try to find by ID 1
            ['name' => 'Equipment'] // If not found, create with this name
        );

        // Create IMSSupplier if it doesn't exist (assuming ims_supplier_id = 1 is also a dependency)
        $supplier = IMSSupplier::firstOrCreate(
            ['id' => 1], // Try to find by ID 1
            ['supplier_name' => 'General Supplier'] // If not found, create with this name
        );


        $stock = IMSStock::create([
            'name' => '12V Cordless Drill',
            'running_number' => 'STK-0001',
            'description' => 'Bosch 12V Cordless Drill for light-duty work',
            'ims_stock_category_id' => $category->id, // Use the ID from the created/found category
            'ims_stock_type_id' => $type->id,       // Use the ID from the created/found type
            'stock_department' => 'marketing',
            'stock_stable_unit' => 100,
            'unit_measure' => 'unit',
            'image' => null,
            'remark' => 'Top seller',
            'activity_logs' => json_encode([
                ['action' => 'created', 'hrms_staff_id' => 1, 'timestamp' => now()]
            ]),
        ]);

        // 🔹 Variant 1
        $variant1 = IMSStockVariant::create([
            'ims_stock_id' => $stock->id,
            'sku_code' => 'DRILL-M6-BLK',
            'size' => 'M6',
            'color' => 'Black',
            'make' => 'Bosch',
            'brand' => 'Bosch',
            'weight' => 1200,
            'default_purchase_cost' => 120.5000,
            'default_sales_price' => 180.0000,
        ]);

        // 🔹 Variant 2
        $variant2 = IMSStockVariant::create([
            'ims_stock_id' => $stock->id,
            'sku_code' => 'DRILL-M10-BLU',
            'size' => 'M10',
            'color' => 'Blue',
            'make' => 'Makita',
            'brand' => 'Makita',
            'weight' => 1500,
            'default_purchase_cost' => 130.0000,
            'default_sales_price' => 190.0000,
        ]);

        // 🔸 Batches for Variant 1
        $batch1a = IMSStockBatch::create([
            'ims_stock_variant_id' => $variant1->id,
            'ims_supplier_id' => $supplier->id, // Use the ID from the created/found supplier
            'batch_no' => 'BATCH-M6-A001',
            'expiry_date' => now()->addYear(),
            'purchase_cost' => 118.0000,
            'sales_price' => 175.0000,
        ]);

        $batch1b = IMSStockBatch::create([
            'ims_stock_variant_id' => $variant1->id,
            'ims_supplier_id' => $supplier->id, // Use the ID from the created/found supplier
            'batch_no' => 'BATCH-M6-A002',
            'expiry_date' => now()->addMonths(6),
            'purchase_cost' => 117.0000,
            'sales_price' => 172.0000,
        ]);

        // 🔸 Batches for Variant 2
        $batch2a = IMSStockBatch::create([
            'ims_stock_variant_id' => $variant2->id,
            'ims_supplier_id' => $supplier->id, // Use the ID from the created/found supplier
            'batch_no' => 'BATCH-M10-B001',
            'expiry_date' => now()->addYear(),
            'purchase_cost' => 125.0000,
            'sales_price' => 185.0000,
        ]);

        $batch2b = IMSStockBatch::create([
            'ims_stock_variant_id' => $variant2->id,
            'ims_supplier_id' => $supplier->id, // Use the ID from the created/found supplier
            'batch_no' => 'BATCH-M10-B002',
            'expiry_date' => now()->addMonths(9),
            'purchase_cost' => 124.0000,
            'sales_price' => 182.0000,
        ]);

        // 🧱 Quantity rows (one per batch)
        foreach ([$batch1a, $batch1b, $batch2a, $batch2b] as $index => $batch) {
            IMSStockQuantity::create([
                'ims_stock_batch_id' => $batch->id,
                'branch_id' => 1,
                'rack_no' => 'R1',
                'shelf_no' => 'S' . ($index + 1),
                'bin_no' => 'BIN-0' . ($index + 1),
                'location_id' => 1,
                'quantity' => rand(5, 50),
            ]);
        }
    }
}