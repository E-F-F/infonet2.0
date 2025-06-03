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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrms_overtime');
        Schema::dropIfExists('hrms_attendance');
    }
};
