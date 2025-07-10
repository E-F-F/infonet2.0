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
        Schema::create('hrms_leave_rank', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('is_active')->default(true)->nullable(false); // is active or not
            $table->softDeletes(); // deleted at field for soft delete (null if record is active)
            $table->timestamps();
        });

        Schema::create('hrms_appraisal_type', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('is_active')->default(true)->nullable(false); // is active or not
            $table->softDeletes(); // deleted at field for soft delete (null if record is active)
            $table->timestamps();
        });

        Schema::create('hrms_pay_group', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('is_active')->default(true); // is active or not
            $table->softDeletes(); // deleted at field for soft delete (null if record is active)
            $table->timestamps();
        });

        Schema::create('hrms_department', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->boolean('is_active')->default(true)->nullable(false); // is active or not
            $table->softDeletes(); // deleted at field for soft delete (null if record is active)
            $table->timestamps();
        });

        // Table for resignation options
        Schema::create('hrms_resign_option', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name')->unique(); // Unique resignation option name
            $table->boolean('is_active')->default(true)->nullable(false); // Status flag
            $table->softDeletes(); // Soft delete field
            $table->timestamps(); // Created at & Updated at
        });

        // Table for pay batch types
        Schema::create('hrms_pay_batch_type', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name')->unique(); // Unique name for batch type
            $table->boolean('is_active')->default(true)->nullable(false); // Status flag
            $table->softDeletes(); // Soft delete field
            $table->timestamps(); // Created at & Updated at
        });

        // Table for pay levels
        Schema::create('hrms_pay_level', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name')->unique(); // Unique pay level name
            $table->boolean('is_active')->default(true)->nullable(false); // Status flag
            $table->softDeletes(); // Soft delete field
            $table->timestamps(); // Created at & Updated at
        });

        Schema::create('hrms_offence_type', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('is_active')->default(true)->nullable(false); // is active or not
            $table->softDeletes(); // deleted at field for soft delete (null if record is active)
            $table->timestamps();
        });

        Schema::create('hrms_training_type', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('is_active')->default(true)->nullable(false); // is active or not
            $table->softDeletes(); // deleted at field for soft delete (null if record is active)
            $table->timestamps();
        });

        Schema::create('hrms_training_award_type', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('is_active')->default(true)->nullable(false); // is active or not
            $table->softDeletes(); // deleted at field for soft delete (null if record is active)
            $table->timestamps();
        });

        Schema::create('hrms_event_type', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('is_active')->default(true)->nullable(false); // is active or not
            $table->softDeletes(); // deleted at field for soft delete (null if record is active)
            $table->timestamps();
        });
        Schema::create('hrms_roster_group', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->enum('status', ['active', 'disabled'])->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hrms_leave_rank');
        Schema::dropIfExists('hrms_appraisal_type');
        Schema::dropIfExists('hrms_pay_group');
        Schema::dropIfExists('hrms_department');
        Schema::dropIfExists('hrms_pay_level');
        Schema::dropIfExists('hrms_pay_batch_type');
        Schema::dropIfExists('hrms_resign_option');

        Schema::dropIfExists('hrms_offence_type');
        Schema::dropIfExists('hrms_training_type');
        Schema::dropIfExists('hrms_training_award_type');
        Schema::dropIfExists('hrms_event_type');
    }
};
