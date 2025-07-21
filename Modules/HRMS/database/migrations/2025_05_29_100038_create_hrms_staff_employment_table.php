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
        Schema::create('hrms_staff_employment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branch')->onDelete('cascade');
            $table->foreignId('hrms_designation_id')->nullable()->constrained('hrms_designation')->onDelete('cascade');
            $table->foreignId('hrms_leave_rank_id')->nullable()->constrained('hrms_leave_rank')->onDelete('cascade');
            $table->foreignId('hrms_pay_group_id')->nullable()->constrained('hrms_pay_group')->onDelete('cascade');
            $table->foreignId('hrms_appraisal_type_id')->nullable()->constrained('hrms_appraisal_type')->onDelete('cascade');
            $table->foreignId('hrms_roster_group_id')->nullable()->constrained('hrms_roster_group')->onDelete('cascade');
            $table->string('employee_number')->nullable();
            $table->date('joining_date')->nullable();
            $table->date('confirmation_date')->nullable();
            $table->date('relieving_date')->nullable();
            $table->unsignedBigInteger('training_period')->default(0);
            $table->unsignedBigInteger('probation_period')->default(0);
            $table->unsignedBigInteger('notice_period')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrms_staff_employment');
    }
};
