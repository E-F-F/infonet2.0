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
        Schema::create('staff_access', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_auth_id')->nullable()->constrained('staff_auth')->onDelete('cascade');
            $table->foreignId('system_access_id')->nullable()->constrained('system_access')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_access');
    }
};
