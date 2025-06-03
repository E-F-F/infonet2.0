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
        Schema::create('hrms_staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_auth_id')->nullable()->constrained('staff_auth')->onDelete('cascade');
            $table->foreignId('hrms_staff_personal_id')->nullable()->constrained('hrms_staff_personal')->onDelete('cascade');
            $table->foreignId('hrms_staff_employment_id')->nullable()->constrained('hrms_staff_employment')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrms_staff');
    }
};
