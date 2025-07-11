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
        Schema::create('hrms_staff_personal', function (Blueprint $table) {
            $table->id();
            // Personal Details
            $table->string('firstName')->nullable(); //
            $table->string('middleName')->nullable(); //
            $table->string('lastName')->nullable(); //
            $table->string('fullName')->nullable(); //
            $table->date('dob')->nullable(); //
            $table->enum('gender', ['Male', 'Female'])->default('Male'); //
            $table->string('marital_status')->nullable(); //
            $table->string('blood_group')->nullable();
            $table->string('religion')->nullable();
            $table->string('race')->nullable();
            // Photo
            $table->string('image_url')->nullable();
            // Other Details
            $table->string('bank_account_no')->nullable(); //
            $table->string('bank_name')->nullable(); //
            $table->string('bank_branch')->nullable(); //
            $table->string('socso_no')->nullable(); //
            $table->string('epf_no')->nullable(); //
            $table->string('income_tax_no')->nullable(); //
            $table->string('ic_no')->nullable(); //
            $table->string('old_ic_no')->nullable(); //
            $table->string('passport_no')->nullable(); //
            $table->string('driving_license_no')->nullable(); //
            $table->string('driving_license_category')->nullable(); //
            $table->string('driving_license_expiry_date')->nullable(); //
            $table->string('gdl_expiry_date')->nullable(); //
            $table->string('work_permit_expiry_date')->nullable(); //
            // Dependent Details
            $table->string('father_name')->nullable(); //
            $table->date('father_dob')->nullable(); //
            $table->string('mother_name')->nullable(); //
            $table->date('mother_dob')->nullable(); //
            $table->string('spouse_name')->nullable(); //
            $table->date('spouse_dob')->nullable(); //
            // Contact Details
            $table->string('mobile_no')->nullable();
            $table->string('work_no')->nullable();
            $table->string('landline_no')->nullable();
            $table->string('work_email')->nullable();
            $table->string('other_email')->nullable();
            $table->string('present_address')->nullable();
            $table->string('present_city')->nullable();
            $table->string('present_state')->nullable();
            $table->string('permanent_address')->nullable();
            $table->string('permanent_city')->nullable();
            $table->string('permanent_state')->nullable();
            $table->string('mailing_address')->nullable();
            // Emergency Contact Details
            $table->string('emergency_contact')->nullable();
            $table->string('emergency_relation')->nullable();
            $table->string('emergency_landline_no')->nullable();
            $table->string('emergency_work_no')->nullable();
            $table->string('emergency_mobile_no')->nullable();
            $table->string('emergency_address')->nullable();
            $table->timestamps();
        });
        Schema::create('hrms_staff_dependent_child', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hrms_staff_personal_id'); // <- Add this line
            $table->string('name');
            $table->date('dob')->nullable();
            $table->string('remark')->nullable();
            $table->timestamps(); // optional but recommended

            $table->foreign('hrms_staff_personal_id')
                ->references('id')
                ->on('hrms_staff_personal')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hrms_staff_dependent_child');
        Schema::dropIfExists('hrms_staff_personal');
    }
};
