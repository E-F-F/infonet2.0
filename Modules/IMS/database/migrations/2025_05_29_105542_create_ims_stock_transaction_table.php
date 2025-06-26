<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ims_shipping_options', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('ims_stock_transaction_purposes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('ims_stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('running_number')->unique();
            $table->foreignId('ims_supplier_id')->nullable()->constrained('ims_suppliers')->nullOnDelete();

            $table->enum('type', ['STOCK IN', 'STOCK OUT', 'STOCK TRANSFER']);
            $table->string('recipient_name')->nullable();

            $table->foreignId('ims_shipping_option_id')->nullable()->constrained('ims_shipping_options')->nullOnDelete();

            $table->enum('status', ['REQUESTED', 'REJECTED', 'APPROVED', 'IN-TRANSIT', 'RECEIVED', 'IN PROGRESS', 'COMPLETED'])->nullable();

            $table->foreignId('ims_stock_transaction_purpose_id')->nullable()->constrained('ims_stock_transaction_purposes')->nullOnDelete();
            $table->string('attachment')->nullable();

            $table->foreignId('from_branch_id')->nullable()->constrained('branch')->cascadeOnDelete();
            $table->foreignId('to_branch_id')->nullable()->constrained('branch')->cascadeOnDelete();

            $table->text('remark')->nullable();
            $table->json('activity_log')->nullable();
            $table->decimal('total_cost', 12, 2)->nullable();

            $table->foreignId('created_by')->nullable()->constrained('hrms_staff')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('hrms_staff')->nullOnDelete();
            $table->foreignId('received_by')->nullable()->constrained('hrms_staff')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('hrms_staff')->nullOnDelete();
            $table->foreignId('rejected_by')->nullable()->constrained('hrms_staff')->nullOnDelete();

            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('received_at')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('rejected_at')->nullable();
        });

        // Renamed from 'ims_item_transaction_items' to 'ims_stock_transaction_items'
        Schema::create('ims_stock_transaction_items', function (Blueprint $table) {
            $table->id();
            // Aligned foreign ID to new transaction table name
            $table->foreignId('ims_stock_transaction_id')->nullable()->constrained('ims_stock_transactions')->cascadeOnDelete();

            // Link to IMS Purchase Orders from your previous migration
            $table->foreignId('ims_purchase_order_id')->nullable()->constrained('ims_purchase_orders')->cascadeOnDelete();

            // This correctly links to the ims_stock_variant for general stock transactions
            $table->foreignId('ims_stock_variant_id')->constrained('ims_stock_variant')->cascadeOnDelete();

            $table->enum('status', ['ON HOLD', 'DELIVERED', 'FROZEN', 'RECEIVED', 'RETURNED', 'DISPOSED'])->nullable();
            $table->unsignedInteger('quantity');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ims_stock_transaction_items');
        Schema::dropIfExists('ims_stock_transactions');
        Schema::dropIfExists('ims_stock_transaction_purposes');
        Schema::dropIfExists('ims_shipping_options');
    }
};
