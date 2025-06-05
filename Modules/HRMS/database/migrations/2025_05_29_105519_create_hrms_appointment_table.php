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
