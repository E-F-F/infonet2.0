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
            $table->id();
            $table->foreignId('hrms_pay_group_id')->constrained('hrms_pay_group')->onDelete('cascade');
            $table->foreignId('hrms_pay_batch_type_id')->constrained('hrms_pay_batch_type')->onDelete('cascade');
            $table->unsignedBigInteger('full_work_day')->default(0);
            $table->text('remarks')->nullable();
            // need to know if has status
            $table->foreignId('created_by')->nullable()->constrained('hrms_staff')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('hrms_staff')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('hrms_staff')->nullOnDelete();
            $table->foreignId('rejected_by')->nullable()->constrained('hrms_staff')->nullOnDelete();

            $table->dateTime('created_at')->nullable(); // Timestamp when record was created/submitted at. Change status to submitted
            $table->dateTime('updated_at')->nullable(); // Timestamp when seen by the admin. Change status to pending
            $table->dateTime('approved_at')->nullable(); // Timestamp when approved by the admin. Change status to approved
            $table->dateTime('rejected_at')->nullable(); // Timestamp when rejected by the admin. Change status to rejected
            $table->softDeletes();
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
