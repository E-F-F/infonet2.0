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
        Schema::create('hrms_leave', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branch')->onDelete('cascade');
            $table->foreignId('hrms_staff_id')->nullable()->constrained('hrms_staff')->onDelete('cascade');
            $table->foreignId('hrms_leave_type_id')->nullable()->constrained('hrms_leave_type')->onDelete('cascade');
            $table->date('date_from');
            $table->enum('session_from', ['AM', 'PM'])->nullable();
            $table->date('date_to');
            $table->enum('session_to', ['AM', 'PM'])->nullable();
            $table->text('leave_purpose');
            $table->string('attachment_url')->nullable();
            $table->enum('status', ['DRAFT', 'CANCELLED', 'SUBMITTED', 'PENDING', 'APPROVED', 'REJECTED'])->default('DRAFT');
            $table->text('remarks')->nullable();

            // Trackers
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
        Schema::dropIfExists('hrms_leave');
    }
};
