<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     *Step-by-step Data Structure Involved
     *Find the stock
     *From ims_stock where name = '12V Drill'
     *Get the id → let’s say ims_stock_id = 1

     *Find the variant
     *From ims_stock_variant where ims_stock_id = 1, and matching the desired variant (e.g., size = M6, color = Black, make = Bosch)
     *Let’s say you get ims_stock_variant_id = 3

     *Find the batch
     *From ims_stock_batch where ims_stock_variant_id = 3
     *Filter by active batch (e.g., not expired), or specific batch_no
     *Let’s say you get ims_stock_batch_id = 10

     *Find the quantity in Branch A
     *From ims_stock_quantity where:
     *ims_stock_batch_id = 10
     *branch_id = Branch A
     *Let’s say current quantity = 20

     *Transfer to Branch B
     *Step A: Subtract 5 from Branch A
     *$branchAQuantity->quantity -= 5;
     *$branchAQuantity->save();
     *Step B: Add 5 to Branch B

     *First check if record exists:
     *$branchBQuantity = ImsStockQuantity::firstOrCreate([
     *    'ims_stock_batch_id' => 10,
     *    'branch_id' => $branchB->id,
     *], [
     *    'quantity' => 0
     *]);
     *Then:
     *$branchBQuantity->quantity += 5;
     *$branchBQuantity->save();
     */
    public function up(): void
    {
        Schema::create('ims_stock_type', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('ims_stock_category', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('ims_stock', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('running_number')->unique();
            $table->text('description')->nullable();
            $table->foreignId('ims_stock_category_id')->nullable()->constrained('ims_stock_category')->cascadeOnDelete();
            $table->foreignId('ims_stock_type_id')->nullable()->constrained('ims_stock_type')->cascadeOnDelete();
            $table->enum('stock_department', ['marketing', 'sparepart', 'vehicle'])->default('marketing');
            // $table->string('stock')->nullable();
            $table->unsignedInteger('stock_stable_unit')->default(0);
            $table->string('unit_measure')->nullable();
            $table->string('image')->nullable();
            $table->text('remark')->nullable();
            $table->json('activity_logs')->nullable(); // e.g., [{"action":"created","hrms_staff_id":1,"timestamp":"..."}]
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ims_stock_variant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ims_stock_id')->constrained('ims_stock')->cascadeOnDelete();

            $table->string('sku_code')->nullable();
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->string('make')->nullable();
            $table->string('brand')->nullable();
            $table->unsignedInteger('weight')->nullable();

            $table->decimal('default_purchase_cost', 12, 4)->nullable();
            $table->decimal('default_sales_price', 12, 4)->nullable();

            $table->timestamps();

            $table->unique(['ims_stock_id', 'size', 'color', 'make', 'weight'], 'stock_variant_unique');
        });

        Schema::create('ims_stock_batch', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ims_stock_variant_id')->constrained('ims_stock_variant')->cascadeOnDelete();
            $table->foreignId('ims_supplier_id')->constrained('ims_supplier')->cascadeOnDelete();

            $table->string('batch_no')->nullable();
            $table->date('expiry_date')->nullable();
            $table->decimal('purchase_cost', 12, 4)->nullable();
            $table->decimal('sales_price', 12, 4)->nullable();

            $table->timestamps();

            $table->unique(['ims_stock_variant_id', 'batch_no', 'ims_supplier_id'], 'unique_batch_combo');
        });

        Schema::create('ims_stock_quantity', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ims_stock_batch_id')->constrained('ims_stock_batch')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branch')->cascadeOnDelete();

            $table->string('rack_no')->nullable();
            $table->string('shelf_no')->nullable();
            $table->string('bin_no');
            $table->foreignId('location_id')->nullable()->constrained('branch')->nullOnDelete();

            $table->unsignedInteger('quantity')->default(0);

            $table->timestamps();

            $table->unique(['ims_stock_batch_id', 'branch_id', 'bin_no']);
        });

        Schema::create('ims_stock_vehicles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ims_stock_id')->constrained('ims_stock')->cascadeOnDelete(); // e.g., Toyota Vios 1.5
            $table->string('vin')->unique(); // Vehicle Identification Number
            $table->string('engine_no')->unique()->nullable();
            $table->string('plate_no')->nullable(); // if already registered

            $table->string('color')->nullable();
            $table->string('year')->nullable();
            $table->date('arrival_date')->nullable();

            $table->foreignId('supplier_id')->nullable()->constrained('ims_supplier')->nullOnDelete();
            $table->decimal('purchase_cost', 12, 2)->nullable();
            $table->decimal('sales_price', 12, 2)->nullable();

            $table->foreignId('branch_id')->constrained('branch')->cascadeOnDelete();
            $table->string('location_code')->nullable(); // e.g., warehouse bay

            $table->enum('status', ['in_stock', 'sold', 'reserved', 'in_transit'])->default('in_stock');

            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('ims_stock_vehicles');
        Schema::dropIfExists('ims_stock_quantity');
        Schema::dropIfExists('ims_stock_batch');
        Schema::dropIfExists('ims_stock_variant');
        Schema::dropIfExists('ims_stock');
        Schema::dropIfExists('ims_stock_category');
        Schema::dropIfExists('ims_stock_type');
    }
};
