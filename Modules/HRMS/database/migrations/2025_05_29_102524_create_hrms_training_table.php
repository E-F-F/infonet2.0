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
        Schema::create('hrms_training', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branch')->onDelete('cascade');
            $table->date('training_start_date');
            $table->date('training_end_date')->nullable();
            $table->foreignId('hrms_training_type_id')->constrained('hrms_training_type')->onDelete('cascade');
            $table->string('training_name');
            $table->foreignId('hrms_training_award_type_id')->constrained('hrms_training_award_type')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('hrms_training_participant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hrms_staff_id')->constrained('hrms_staff')->onDelete('cascade');
            $table->foreignId('hrms_training_id')->constrained('hrms_training')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrms_training_participant');
        Schema::dropIfExists('hrms_training');
    }
};
