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
        Schema::create('hrms_leave_type', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->unsignedInteger('default_no_of_days')->nullable();
            $table->enum('status', ['active', 'inactive'])->nullable(); //dropdown of status
            $table->string('earned_rules')->nullable(); // earned rules discussed with HR team(shirly)
            $table->boolean('need_blocking')->default(false);
            $table->boolean('leave_model')->default(false);
            $table->boolean('require_attachment')->default(false);
            $table->boolean('apply_by_hours')->default(false);
            $table->boolean('allow_carry_forward')->default(false);
            $table->unsignedInteger('apply_within_days')->nullable();
            $table->string('background_color')->nullable();
            $table->text('remarks')->nullable();
            $table->string('replacement_shift')->nullable(); // discussed with HR team(shirly) dropdown
            $table->softDeletes(); // deleted at field for soft delete (null if record is active)
            $table->timestamps();
        });

        Schema::create('hrms_leave_model', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hrms_leave_type_id')->constrained('hrms_leave_type')->onDelete('cascade'); // leave type id
            $table->foreignId('hrms_leave_rank_id')->constrained('hrms_leave_rank')->onDelete('cascade'); // leave type id
            $table->integer('year_of_service'); // The year for which the entitlement applies (e.g., 2025)
            $table->unsignedInteger('entitled_days')->nullable(); // days per year
            $table->float('carry_forward_days')->nullable(); // carry forward days
            $table->unique(
                ['hrms_leave_type_id', 'hrms_leave_rank_id', 'year_of_service'],
                'leave_model_type_rank_year_unique'
            );
            $table->softDeletes(); // deleted at field for soft delete (null if record is active)
            $table->timestamps();
        });

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

        Schema::create('hrms_leave_entitlement', function (Blueprint $table) {
            $table->id(); // Primary key for the table

            // Foreign key to the hrms_staff table
            // Assuming 'hrms_staff' table exists and has an 'id' primary key
            $table->foreignId('hrms_leave_type_id')->constrained('hrms_leave_type')->onDelete('cascade'); // leave type id
            $table->foreignId('hrms_staff_id')->constrained('hrms_staff')->onDelete('cascade'); // hrms_staff_id

            $table->float('entitled_days'); // Total leave days allocated for the year
            $table->float('consumed_days')->default(0.0); // Days consumed from the entitlement
            $table->float('remaining_days'); // Calculated remaining days (entitled - consumed)

            $table->integer('year'); // The year for which the entitlement applies (e.g., 2025)

            $table->timestamps(); // Adds created_at and updated_at columns

            // Add a unique constraint to ensure one entitlement record per staff per year
            $table->unique(['hrms_staff_id', 'hrms_leave_type_id', 'year'], 'unique_staff_year_entitlement');
        });

        Schema::create('hrms_leave_adjustment_reason', function (Blueprint $table) {
            $table->id(); // Primary key for the table
            $table->string('reason_name'); // Name of the adjustment reason
            $table->enum('built_in', ['Normal', 'NONE'])->default('Normal'); // Optional description or notes about the reason
            $table->boolean('is_active')->default(true); // Flag indicating if the reason is active or not
            $table->timestamps(); // Adds created_at and updated_at columns
            $table->softDeletes(); // Soft delete column
        });

        Schema::create('hrms_leave_adjustment', function (Blueprint $table) {
            $table->id(); // Primary key for the table
            $table->foreignId('hrms_staff_id')->nullable()->constrained('hrms_staff')->onDelete('cascade');
            $table->foreignId('hrms_leave_type_id')->nullable()->constrained('hrms_leave_type')->onDelete('cascade');
            $table->foreignId('adjustment_reason_id')->nullable()->constrained('hrms_leave_adjustment_reason')->onDelete('cascade');
            $table->decimal('days', 8, 2);
            $table->date('effective_date'); // Date on which the adjustment becomes effective
            $table->text('remarks')->nullable(); // Additional details about the adjustment
            $table->timestamps(); // Adds created_at and updated_at columns
            $table->softDeletes(); // Soft delete column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrms_leave');
        Schema::dropIfExists('hrms_leave_entitlement');
        Schema::dropIfExists('hrms_leave_adjustment_reason');
        Schema::dropIfExists('hrms_leave_adjustment');
        Schema::dropIfExists('hrms_leave_model');
        Schema::dropIfExists('hrms_leave_type');
    }
};
