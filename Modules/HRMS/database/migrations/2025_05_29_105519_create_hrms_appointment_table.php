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
        Schema::create('hrms_attendance_station', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->foreignId('branch_id')->constrained('branch')->onDelete('cascade');
            $table->string('serial_number')->nullable();
            $table->string('hashed_password')->nullable();
            $table->boolean('is_active')->default(true)->nullable(false);
            $table->string('sensor_id')->nullable();
            $table->boolean('auto_inout')->default(true)->nullable(false);
            $table->timestamps();
        });

        Schema::create('hrms_attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hrms_staff_id')->constrained('hrms_staff')->onDelete('cascade');
            $table->date('attendance_date');
            $table->time('morning_clockIn')->nullable();
            $table->time('morning_clockOut')->nullable();
            $table->enum('morning_status', ['present', 'absent', 'late', 'onleave'])->nullable();
            $table->time('afternoon_clockIn')->nullable();
            $table->time('afternoon_clockOut')->nullable();
            $table->enum('afternoon_status', ['present', 'absent', 'late', 'onleave'])->nullable();
            $table->double('total_working_hours', 8, 2)->nullable();
            $table->text('remark')->nullable();
            $table->unique(['hrms_staff_id', 'attendance_date']);
            $table->timestamps();
        });

        // Schema::create('hrms_attendance', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('hrms_staff_id')->constrained('hrms_staff')->onDelete('cascade');
        //     $table->date('attendance_date');
        //     $table->time('time_in')->nullable();
        //     $table->time('break_time_out')->nullable();
        //     $table->enum('morning_status', ['present', 'absent', 'late', 'onleave'])->nullable();
        //     $table->time('break_time_in')->nullable();
        //     $table->time('time_out')->nullable();
        //     $table->enum('afternoon_status', ['present', 'absent', 'late', 'onleave'])->nullable();
        //     $table->double('total_working_hours', 8, 2)->nullable();
        //     $table->text('remark')->nullable();
        //     $table->unique(['hrms_staff_id', 'attendance_date']);
        //     $table->timestamps();
        // });

        Schema::create('hrms_overtime', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hrms_staff_id')->constrained('hrms_staff')->onDelete('cascade');
            $table->date('overtime_date');
            $table->time('overtime_clockIn')->nullable();
            $table->time('overtime_clockOut')->nullable();
            $table->double('overtime_total_hours', 8, 2)->nullable();
            $table->enum('overtime_status', ['present', 'absent', 'late', 'onleave'])->nullable();
            $table->unique(['hrms_staff_id', 'overtime_date']);
            $table->json('activity_logs')->nullable();
            $table->timestamps();
        });
        
        Schema::create('hrms_appointment', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_subject');
            $table->text('appointment_description');
            $table->unsignedBigInteger('appointment_recipient')->nullable();
            $table->foreign('appointment_recipient')->references('id')->on('hrms_staff')->nullOnDelete();
            $table->date('appointment_date');
            $table->time('appointment_start_time');
            $table->time('appointment_end_time');
            $table->text('appointment_remark');
            $table->string('appointment_status');
            $table->text('appointment_reviewer_remark');

            $table->unsignedBigInteger('created_by')->nullable();
            $table->date('created_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->date('updated_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->dateTime('rejected_at')->nullable();
            $table->foreign('created_by')->references('id')->on('hrms_staff')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('hrms_staff')->nullOnDelete();
            $table->foreign('approved_by')->references('id')->on('hrms_staff')->nullOnDelete();
            $table->foreign('rejected_by')->references('id')->on('hrms_staff')->nullOnDelete();
            $table->json('activity_logs')->nullable();
            // Delete
            $table->boolean('is_active')->default(true)->nullable(false); // is active or not
            $table->softDeletes(); // deleted at field for soft delete (null if record is active)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrms_appointment');
    }
};
