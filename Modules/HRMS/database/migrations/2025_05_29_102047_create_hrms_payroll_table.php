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

        Schema::create('hrms_pay_cycle', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['active', 'disabled']);
            $table->text('remarks');
            $table->boolean('is_active')->default(true)->nullable(false); // is active or not
            $table->timestamps();
        });

        Schema::create('hrms_payroll_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hrms_pay_batch_type_id')->nullable()->constrained('hrms_pay_batch_type')->onDelete('cascade');
            $table->enum('component_type', ['earning', 'allowance', 'deduction'])->nullable();
            $table->string('code');
            $table->string('name');
            $table->enum('calculated_by', ['fixed', 'byWorkDay', 'deduction'])->nullable();
            $table->enum('parent_component', ['undecided', 'undecided2'])->nullable();
            $table->enum('show_info_in_payslip', ['none', 'showoriginalammountifdeducted'])->nullable();
            $table->boolean('entitled_epf')->default(true);
            $table->string('max_ammount_for_lookup')->nullable();
            $table->boolean('entitled_socso')->default(true);
            $table->boolean('entitled_eis')->default(true);
            $table->boolean('entitled_hrdf')->default(true);
            $table->boolean('entitled_pcb')->default(true);
            $table->string('max_ammount');
            $table->text('remarks')->nullable();
            $table->integer('position_no');
            $table->softDeletes(); // deleted at field for soft delete (null if record is active)
            $table->timestamps();
        });

        Schema::create('hrms_pay_lookup', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hrms_payroll_components_id')->nullable()->constrained('hrms_payroll_components')->onDelete('cascade');
            $table->date('effective_date');
            $table->string('ref_doc_name');
            $table->enum('status', ['active', 'disabled']);
            $table->text('remarks')->nullable();
            $table->integer('position_no');
            $table->softDeletes(); // deleted at field for soft delete (null if record is active)
            $table->timestamps();
        });

        // Schema::create('hrms_payroll', function (Blueprint $table) {
        //     $table->id(); // Primary key

        //     $table->foreignId('hrms_pay_group_id') // Reference to pay group
        //         ->constrained('hrms_pay_group')
        //         ->onDelete('cascade');

        //     $table->foreignId('hrms_pay_batch_type_id') // Reference to pay batch type
        //         ->constrained('hrms_pay_batch_type')
        //         ->onDelete('cascade');

        //     $table->unsignedBigInteger('full_work_day')->default(0); // Number of full work days for the cycle

        //     $table->text('remarks')->nullable(); // Optional notes or remarks

        //     // Optional: Status field to simplify workflow tracking
        //     $table->enum('status', ['draft', 'submitted', 'pending', 'approved', 'rejected'])->default('draft'); // Workflow status

        //     // References to HRMS staff (tracking action owners)
        //     $table->foreignId('created_by')->nullable()->constrained('hrms_staff')->nullOnDelete(); // Who created/submitted
        //     $table->foreignId('updated_by')->nullable()->constrained('hrms_staff')->nullOnDelete(); // Who last updated
        //     $table->foreignId('approved_by')->nullable()->constrained('hrms_staff')->nullOnDelete(); // Who approved
        //     $table->foreignId('rejected_by')->nullable()->constrained('hrms_staff')->nullOnDelete(); // Who rejected

        //     // Timestamps for workflow events
        //     $table->dateTime('created_at')->nullable(); // Submission timestamp
        //     $table->dateTime('updated_at')->nullable(); // Last modified timestamp
        //     $table->dateTime('approved_at')->nullable(); // When approved
        //     $table->dateTime('rejected_at')->nullable(); // When rejected

        //     $table->softDeletes(); // Soft delete support
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('hrms_payroll');
        Schema::dropIfExists('hrms_pay_lookup');
        Schema::dropIfExists('hrms_pay_cycle');
    }
};
