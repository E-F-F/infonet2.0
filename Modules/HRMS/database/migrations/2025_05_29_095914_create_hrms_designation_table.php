<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hrms_designation', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId('hrms_department_id')->nullable()->constrained('hrms_department')->onDelete('cascade');
            $table->foreignId('parent_designation_id')->nullable()->constrained('hrms_designation')->onDelete('cascade');
            $table->foreignId('hrms_leave_rank_id')->nullable()->constrained('hrms_leave_rank')->onDelete('cascade');
            $table->boolean('is_active')->default(true)->nullable(false); // is active or not
            $table->softDeletes(); // deleted at field for soft delete (null if record is active)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hrms_designation');
    }
};
