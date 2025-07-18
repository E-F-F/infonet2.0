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
        Schema::create('hrms_offence', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branch')->onDelete('cascade');
            $table->foreignId('hrms_staff_id')->constrained('hrms_staff')->onDelete('cascade');
            $table->date('issue_date');
            $table->foreignId('hrms_offence_type_id')->constrained('hrms_offence_type')->onDelete('cascade');
            $table->text('description');
            $table->foreignId('hrms_offence_action_taken_id')->constrained('hrms_offence_action_taken')->onDelete('cascade');
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('hrms_staff')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('hrms_staff')->nullOnDelete();
            $table->softDeletes();
        });

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

        Schema::create('hrms_event', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hrms_event_type_id')->constrained('hrms_event_type')->onDelete('cascade');
            $table->string('title');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('event_company');
            $table->string('event_branch');
            $table->string('event_venue');
            $table->text('remarks')->nullable();
            $table->json('activity_logs')->nullable();
            $table->timestamps();

            $table->boolean('is_active')->default(true)->nullable(false); // is active or not
            $table->softDeletes(); // deleted at field for soft delete (null if record is active)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrms_offence');
        Schema::dropIfExists('hrms_training_participant');
        Schema::dropIfExists('hrms_training');
        Schema::dropIfExists('hrms_event');
    }
};
