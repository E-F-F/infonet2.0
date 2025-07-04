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
        Schema::create('hrms_leave_type', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->unsignedInteger('default_no_of_days')->nullable();
            $table->enum('status', ['active', 'inactive'])->nullable(); //dropdown of status
            $table->string('earned_rules')->nullable(); // earned rules discussed with HR team(shirly)
            $table->boolean('need_blocking')->default(false);
            $table->boolean('leave_model')->default(false);
            $table->boolean('require_attachment')->default(false);
            $table->boolean('apply_by_hours')->default(false);
            $table->boolean('allow_carry_forward')->default(false);
            $table->unsignedInteger('apply_within_days')->nullable();
            $table->string('background_color')->nullable();
            $table->text('remarks')->nullable();
            $table->string('replacement_shift')->nullable(); // discussed with HR team(shirly) dropdown
            $table->softDeletes(); // deleted at field for soft delete (null if record is active)
            $table->timestamps();
        });

        Schema::create('hrms_leave_model', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hrms_leave_type_id')->constrained('hrms_leave_type')->onDelete('cascade'); // leave type id
            $table->foreignId('hrms_leave_rank_id')->constrained('hrms_leave_rank')->onDelete('cascade'); // leave type id
            $table->integer('year_of_service'); // The year for which the entitlement applies (e.g., 2025)
            $table->unsignedInteger('entitled_days')->nullable(); // days per year
            $table->float('carry_forward_days')->nullable(); // carry forward days
            $table->unique(
                ['hrms_leave_type_id', 'hrms_leave_rank_id', 'year_of_service'],
                'leave_model_type_rank_year_unique'
            );
            $table->softDeletes(); // deleted at field for soft delete (null if record is active)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrms_leave_model');
        Schema::dropIfExists('hrms_leave_type');
    }
};
