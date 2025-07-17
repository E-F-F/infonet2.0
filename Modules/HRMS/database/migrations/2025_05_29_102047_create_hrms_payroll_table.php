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
        Schema::create('hrms_payroll', function (Blueprint $table) {
            $table->id(); // Primary key

            $table->foreignId('hrms_pay_group_id') // Reference to pay group
                ->constrained('hrms_pay_group')
                ->onDelete('cascade');

            $table->foreignId('hrms_pay_batch_type_id') // Reference to pay batch type
                ->constrained('hrms_pay_batch_type')
                ->onDelete('cascade');

            $table->unsignedBigInteger('full_work_day')->default(0); // Number of full work days for the cycle

            $table->text('remarks')->nullable(); // Optional notes or remarks

            // Optional: Status field to simplify workflow tracking
            $table->enum('status', ['draft', 'submitted', 'pending', 'approved', 'rejected'])->default('draft'); // Workflow status

            // References to HRMS staff (tracking action owners)
            $table->foreignId('created_by')->nullable()->constrained('hrms_staff')->nullOnDelete(); // Who created/submitted
            $table->foreignId('updated_by')->nullable()->constrained('hrms_staff')->nullOnDelete(); // Who last updated
            $table->foreignId('approved_by')->nullable()->constrained('hrms_staff')->nullOnDelete(); // Who approved
            $table->foreignId('rejected_by')->nullable()->constrained('hrms_staff')->nullOnDelete(); // Who rejected

            // Timestamps for workflow events
            $table->dateTime('created_at')->nullable(); // Submission timestamp
            $table->dateTime('updated_at')->nullable(); // Last modified timestamp
            $table->dateTime('approved_at')->nullable(); // When approved
            $table->dateTime('rejected_at')->nullable(); // When rejected

            $table->softDeletes(); // Soft delete support
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrms_payroll');
    }
};
