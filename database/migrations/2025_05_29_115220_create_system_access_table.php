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
        Schema::create('system_access', function (Blueprint $table) {
            $table->id();
            $table->string('access_name');
            $table->foreignId('branch_id')->nullable()->constrained('branch')->onDelete('cascade');
            $table->boolean('hrms')->default(false);
            // $table->boolean('payroll')->default(false);
            // $table->boolean('inventory')->default(false);
            // $table->boolean('accounting')->default(false);
            // $table->boolean('reports')->default(false);
            // $table->boolean('settings')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_access');
    }
};
