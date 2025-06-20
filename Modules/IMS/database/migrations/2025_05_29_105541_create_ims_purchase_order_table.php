<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tax types, e.g., SST 6%, VAT, etc.
        Schema::create('ims_tax', function (Blueprint $table) {
            $table->id();
            $table->string('tax_name');
            $table->decimal('tax_percentage', 5, 2);
            $table->timestamps();
        });

        /**
         * Purchase Order Table
         * - One row per PO created
         * - Can contain multiple items
         * - Used to track the full procurement transaction
         */
        Schema::create('ims_purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('ims_suppliers')->cascadeOnDelete();
            $table->foreignId('tax_id')->constrained('ims_tax')->cascadeOnDelete();
            $table->date('expected_receipt_date');
            $table->text('billing_address');
            $table->text('shipping_address');
            $table->string('tracking_ref')->unique(); // E.g. courier consignment number
            $table->string('purchase_order_running_number')->unique(); // E.g. PO2025-001
            $table->text('purchase_order_notes')->nullable(); // External notes
            $table->text('purchase_internal_notes')->nullable(); // Internal only

            $table->enum('status', ['draft', 'submitted', 'received', 'cancelled'])->default('draft');

            // User tracking
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();

            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('received_at')->nullable(); // Date final stock received
            $table->dateTime('approved_at')->nullable();
        });

        /**
         * Purchase Order Items
         * - Line items for each PO
         * - Allows tracking quantity ordered vs quantity received
         * - Partial receipt happens here: quantity_received < quantity_ordered
         */
        Schema::create('ims_purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('ims_purchase_orders')->cascadeOnDelete();
            $table->foreignId('ims_stock_variant_id')->constrained('ims_stock_variant')->cascadeOnDelete();

            $table->unsignedInteger('quantity_ordered');
            $table->unsignedInteger('quantity_received')->default(0); // Update incrementally on each GRN
            $table->decimal('unit_cost', 12, 4);

            $table->timestamps();
        });

        /**
         * GRN - Goods Receipt Note
         * - One row per actual delivery/receipt
         * - Can contain part or all of the PO items
         * - If the supplier delivers in 2 batches, 2 GRNs will be created
         */
        Schema::create('ims_grns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('ims_purchase_orders')->cascadeOnDelete();

            $table->string('grn_number')->unique(); // e.g. GRN-2025-001
            $table->date('grn_date')->default(now());

            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        /**
         * GRN Items
         * - Tracks what stock was received under each GRN
         * - Links back to purchase_order_items to update quantity_received
         * - Allows partial receiving per GRN
         */
        Schema::create('ims_grn_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grn_id')->constrained('ims_grns')->cascadeOnDelete();
            $table->foreignId('purchase_order_item_id')->constrained('ims_purchase_order_items')->cascadeOnDelete();

            $table->unsignedInteger('quantity_received'); // e.g., received only 5 out of 10 ordered

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ims_grn_items');
        Schema::dropIfExists('ims_grns');
        Schema::dropIfExists('ims_purchase_order_items');
        Schema::dropIfExists('ims_purchase_orders');
        Schema::dropIfExists('ims_tax');
    }
};
