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
        Schema::create('hrms_staff_employment_history', function (Blueprint $table) {
            $table->id();

            // Foreign key to staff
            $table->unsignedBigInteger('hrms_staff_id');

            // Columns based on screenshot
            $table->string('organization');   // e.g. UNIDECK GLOBAL SDN BHD
            $table->string('position');       // e.g. ADMIN, TECHNICAL INTERN
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('comment')->nullable(); // e.g. "4 MONTHS", "2023 - 2024"

            $table->timestamps();

            // Index + FK
            $table->index('hrms_staff_id', 'idx_empdetail_staff');
            $table->foreign('hrms_staff_id')
                ->references('id')
                ->on('hrms_staff')
                ->onDelete('cascade'); // delete employment history if staff is deleted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrms_staff_employment_history');
    }
};
