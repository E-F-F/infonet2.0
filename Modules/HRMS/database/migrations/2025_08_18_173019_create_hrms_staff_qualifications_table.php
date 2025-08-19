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
        Schema::create('hrms_staff_qualifications', function (Blueprint $table) {
            $table->id();

            // Must match hrms_staff.id type. If hrms_staff uses bigIncrements (typical), keep unsignedBigInteger.
            $table->unsignedBigInteger('hrms_staff_id');

            // Columns based on your screenshot
            $table->string('qualification');         // e.g. Bachelor, Diploma
            $table->string('institution')->nullable(); // e.g. UCSI UNIVERSITY
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('marks_grade')->nullable(); // e.g. "PENDING", "3.75 GPA", "First Class"

            $table->timestamps();

            // Index + FK
            $table->index('hrms_staff_id', 'idx_qual_staff');
            $table->foreign('hrms_staff_id')
                ->references('id')
                ->on('hrms_staff')
                ->onDelete('cascade'); // remove quals when staff is deleted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrms_staff_qualifications');
    }
};
