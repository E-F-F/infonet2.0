<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hrms_holiday', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->date('holiday_date')->unique();
            $table->date('effective_date');
            $table->enum('type', ['public holiday', 'special leave'])->default('public holiday');
            $table->enum('status', ['active', 'disabled'])->default('active');
            $table->timestamps();
        });

        Schema::create('hrms_offday', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('holiday_date');
            $table->date('effective_date');
            $table->enum('recurring_interval', ['weekly', 'one time', 'monthly', 'quarterly', 'annually'])->default('weekly');
            $table->date('recurring_end_date');
            $table->enum('holiday_type', ['sundayOffday', 'special'])->default('special');
            $table->enum('status', ['active', 'disabled'])->default('active');
            $table->timestamps();
        });

        Schema::create('hrms_roster_shift', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->time('time_in');
            $table->time('time_out');
            $table->time('break_time_in')->nullable();
            $table->time('break_time_out')->nullable();
            $table->boolean('has_lunch_break')->default(false);
            $table->integer('break_minutes')->nullable();
            $table->time('second_break_time_in')->nullable();
            $table->time('second_break_time_out')->nullable();
            $table->integer('second_break_minutes')->nullable();
            $table->boolean('is_lunch_break')->default(false);
            $table->time('ot_time_in')->nullable();
            $table->time('ot_time_out')->nullable();
            $table->integer('ot_work_minutes')->nullable();
            $table->boolean('full_shift')->default(false);
            $table->boolean('flexi')->default(false);
            $table->boolean('late_offset_ot')->default(false);
            $table->string('alt_shift')->nullable();
            $table->string('ot1_component')->nullable();
            $table->string('ot2_component')->nullable();
            $table->integer('ot2_component_hours')->nullable();
            $table->integer('late_in_rounding_minutes')->nullable();
            $table->integer('early_out_rounding_minutes')->nullable();
            $table->integer('break_late_in_rounding_minutes')->nullable();
            $table->integer('break_late_in_minimum_minutes')->nullable();
            $table->integer('ot_round_down_minutes')->nullable();
            $table->integer('ot_round_up_adj_minutes')->nullable();
            $table->integer('ot_minimum_minutes')->nullable();
            $table->integer('ot_days')->nullable();
            $table->string('type_for_leave')->nullable();
            $table->enum('status', ['active', 'disabled'])->default('active');
            $table->foreignId('branch_id')->nullable()->constrained('branch')->onDelete('cascade');
            $table->boolean('allowed_thumbprint_once')->default(false);
            $table->string('background_color')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('hrms_roster', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branch')->onDelete('cascade');
            $table->integer('year')->unique();
            $table->foreignId('roster_group_id')->nullable()->constrained('hrms_roster_group')->onDelete('cascade');

            $table->foreignId('default_roster_shift_workday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('default_roster_shift_public_holiday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('default_roster_shift_offday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('default_roster_shift_company_halfoffday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');

            // Manual day-wise shift pattern
            foreach (['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'] as $day) {
                $table->foreignId("{$day}_shift_workday")->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
                $table->foreignId("{$day}_shift_public_holiday")->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
                $table->foreignId("{$day}_shift_offday")->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
                $table->foreignId("{$day}_shift_company_halfoffday")->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            }

            $table->date('effective_date');
            $table->enum('status', ['active', 'disabled'])->default('active');
            $table->timestamps();
        });

        Schema::create('hrms_roster_day_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('roster_id')->constrained('hrms_roster')->onDelete('cascade');
            $table->date('roster_date');
            $table->enum('day_type', ['workday', 'public_holiday', 'offday', 'company_halfoffday']);
            $table->foreignId('shift_id')->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->boolean('is_override')->default(false);
            $table->foreignId('hrms_staff_id')->nullable()->constrained('hrms_staff')->onDelete('cascade'); // optional
            $table->timestamps();

            $table->unique(['roster_id', 'roster_date', 'hrms_staff_id']);
        });

        Schema::create('hrms_staff_roster_group_assignment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hrms_staff_id')->constrained('hrms_staff')->onDelete('cascade');
            $table->foreignId('roster_group_id')->constrained('hrms_roster_group')->onDelete('cascade');
            $table->date('effective_date');
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hrms_staff_roster_group_assignment');
        Schema::dropIfExists('hrms_roster_day_assignments');
        Schema::dropIfExists('hrms_roster');
        Schema::dropIfExists('hrms_roster_shift');
        Schema::dropIfExists('hrms_offday');
        Schema::dropIfExists('hrms_holiday');
    }
};
