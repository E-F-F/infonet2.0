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
        Schema::create('hrms_holiday', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->date('holiday_date')->unique();
            $table->date('effective_date'); //Cuti Ganti
            $table->enum('type', ['public holiday', 'special leave'])->default('public holiday');
            $table->enum('status', ['active', 'disabled'])->default('active');
            $table->timestamps();
        });

        Schema::create('hrms_offday', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('holiday_date');
            $table->date('effective_date'); //Cuti Ganti
            $table->enum('recurring_interval', ['weekly', 'one time', 'monthly', 'quarterly', 'annually'])->default('weekly');
            $table->date('recurring_end_date');
            $table->enum('holiday_type', ['sundayOffday', 'special'])->default('special');
            $table->enum('status', ['active', 'disabled'])->default('active');
            $table->timestamps();
        });



        Schema::create('hrms_roster_shift', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            // Clock In Time & Clock Out Time
            $table->time('time_in');
            $table->time('time_out');
            // Break Time
            $table->time('break_time_in')->nullable();
            $table->time('break_time_out')->nullable();
            $table->boolean('has_lunch_break')->default(false);
            $table->integer('break_minutes')->nullable();
            // Second Break Time(IF OT)
            $table->time('second_break_time_in')->nullable();
            $table->time('second_break_time_out')->nullable();
            $table->integer('second_break_minutes')->nullable();
            $table->boolean('is_lunch_break')->default(false);
            // OT
            $table->time('ot_time_in')->nullable();
            $table->time('ot_time_out')->nullable();
            $table->integer('ot_work_minutes')->nullable();
            // Full Shift
            $table->boolean('full_shift')->default(false); //full_shift — Is this a full-day, fixed-time shift?
            $table->boolean('flexi')->default(false); //flexi — Is this a flexible working hours shift?

            $table->boolean('late_offset_ot')->default(false); //\\ Offset/Deduct OT if late time in
            // Dropdown dont know what this is for
            $table->string('alt_shift')->nullable();
            $table->string('ot1_component')->nullable();
            $table->string('ot2_component')->nullable();
            $table->integer('ot2_component_hours')->nullable();
            // Still not understand the logic
            $table->integer('late_in_rounding_minutes')->nullable(); //Positive for round up, negative for round down
            $table->integer('early_out_rounding_minutes')->nullable(); //Positive for round up, negative for round down
            $table->integer('break_late_in_rounding_minutes')->nullable(); //Positive for round up, negative for round down
            $table->integer('break_late_in_minimum_minutes')->nullable(); // Blank for no late approval
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
            $table->date('year')->unique();
            $table->foreignId('roster_group_id')->nullable()->constrained('hrms_roster_group')->onDelete('cascade');
            $table->foreignId('default_roster_shift_workday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('default_roster_shift_public_holiday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('default_roster_shift_offday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('default_roster_shift_company_halfoffday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('sunday_shift_workday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('sunday_shift_public_holiday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('sunday_shift_offday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('sunday_shift_company_halfoffday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('monday_shift_workday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('monday_shift_public_holiday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('monday_shift_offday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('monday_shift_company_halfoffday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('tuesday_shift_workday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('tuesday_shift_public_holiday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('tuesday_shift_offday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('tuesday_shift_company_halfoffday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('wednesday_shift_workday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('wednesday_shift_public_holiday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('wednesday_shift_offday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('wednesday_shift_company_halfoffday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('thursday_shift_workday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('thursday_shift_public_holiday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('thursday_shift_offday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('thursday_shift_company_halfoffday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('friday_shift_workday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('friday_shift_public_holiday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('friday_shift_offday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('friday_shift_company_halfoffday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('saturday_shift_workday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('saturday_shift_public_holiday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('saturday_shift_offday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->foreignId('saturday_shift_company_halfoffday')->nullable()->constrained('hrms_roster_shift')->onDelete('cascade');
            $table->date('effective_date');
            $table->timestamps();
        });

        Schema::create('hrms_staff_roster_group_assignment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hrms_staff_id')->constrained('hrms_staff')->onDelete('cascade');
            $table->foreignId('roster_group_id')->constrained('hrms_roster_group')->onDelete('cascade');
            $table->date('effective_date'); // When this assignment starts
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrms_roster');
        Schema::dropIfExists('hrms_roster_shift');
        Schema::dropIfExists('hrms_offday');
        Schema::dropIfExists('hrms_holiday');
    }
};
