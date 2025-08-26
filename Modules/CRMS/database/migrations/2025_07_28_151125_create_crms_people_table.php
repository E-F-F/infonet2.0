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
        // Lookup Tables
        Schema::create('crms_people_race', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('crms_people_occupation', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('crms_business_nature', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('crms_people_income', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Main CRM Tables
        Schema::create('crms_company', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('sst_reg_no')->nullable();
            $table->string('gst_reg_no')->nullable();
            $table->string('company_size')->nullable();
            $table->string('sector')->nullable();
            $table->foreignId('crms_business_nature_id')->nullable()->constrained('crms_business_nature')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('crms_people', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hrms_staff_id')->nullable()->constrained('hrms_staff')->onDelete('cascade');
            $table->foreignId('crms_company_id')->nullable()->constrained('crms_company')->onDelete('cascade');
            $table->foreignId('crms_people_race_id')->nullable()->constrained('crms_people_race')->onDelete('cascade');
            $table->foreignId('crms_people_income_id')->nullable()->constrained('crms_people_income')->onDelete('cascade');
            $table->foreignId('crms_people_occupation_id')->nullable()->constrained('crms_people_occupation')->onDelete('cascade');
            $table->foreignId('crms_business_nature_id')->nullable()->constrained('crms_business_nature')->onDelete('cascade');

            $table->string('people_type')->nullable();
            $table->string('people_status')->nullable();
            $table->string('grading')->nullable();
            $table->date('last_contact_date')->nullable();
            $table->boolean('under_company_registration')->nullable();
            
            // Personal Details
            $table->string('customer_name');
            $table->string('id_type')->nullable();
            $table->string('id_number')->nullable();
            $table->string('tin')->nullable();
            $table->date('dob')->nullable();
            $table->string('owner_phone');
            $table->string('home_no')->nullable();
            $table->string('email')->nullable();
            
            // Other Contact
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->string('office_no')->nullable();
            $table->string('fax_no')->nullable();
            
            // Addresses
            $table->string('primary_address');
            $table->string('primary_postcode');
            $table->string('primary_city');
            $table->string('primary_state');
            $table->string('primary_country');
            $table->string('postal_address')->nullable();
            $table->string('postal_postcode')->nullable();
            $table->string('postal_city')->nullable();
            $table->string('postal_state')->nullable();
            $table->string('postal_country')->nullable();
            
            $table->string('zone')->nullable();
            $table->string('religion')->nullable();
            $table->string('marital_status')->nullable();
            $table->boolean('is_corporate')->default(false);
            $table->text('lifestyle_interest')->nullable();
            $table->string('link_customer')->nullable();
            $table->string('account_terms')->nullable();
            $table->string('price_scheme')->nullable();
            $table->timestamps();
        });

        Schema::create('crms_vehicle_info', function (Blueprint $table) {
            $table->id();
            
            // Relations
            $table->foreignId('branch_id')->nullable()->constrained('branch')->onDelete('cascade');
            $table->foreignId('hrms_staff_id')->nullable()->constrained('hrms_staff')->onDelete('cascade');
            $table->foreignId('crms_people_id')->nullable()->constrained('crms_people')->onDelete('cascade');
            $table->foreignId('ims_vehicle_make_id')->nullable()->constrained('ims_vehicle_make')->onDelete('cascade');
            $table->foreignId('ims_vehicle_model_id')->nullable()->constrained('ims_vehicle_model')->onDelete('cascade');
            
            // Vehicle Details
            $table->string('type')->nullable(); // new, recon, rebuild, used.
            $table->string('registration_no')->nullable();
            $table->string('stock_no')->nullable();
            $table->date('date_in')->nullable();
            $table->date('date_to_hq')->nullable();
            $table->string('colour')->nullable();
            $table->string('body')->nullable();
            $table->string('chassis_no')->nullable();
            $table->string('engine_no')->nullable();
            $table->year('manufacture_year')->nullable();
            
            // Accessories
            $table->string('accessories_standard')->nullable();
            $table->string('accessories_optional')->nullable();
            
            // Pricing
            $table->decimal('recommended_net_selling_price', 15, 2)->nullable();
            $table->decimal('accessories_otr_cost', 15, 2)->nullable();
            $table->decimal('recommended_otr_price_with_std_accessories', 15, 2)->nullable();
            
            // Notes
            $table->text('notes')->nullable();
            $table->timestamps();
        });
        
        Schema::create('crms_people_marketing_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crms_people_id')->nullable()->constrained('crms_people')->onDelete('cascade');
            $table->string('other_related_business');
            $table->string('business_current_future');
            $table->string('repurchase_timing');
            $table->timestamps();
        });

        Schema::create('crms_people_quotation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crms_people_id')->nullable()->constrained('crms_people')->onDelete('cascade');
            $table->foreignId('ims_vehicle_make_id')->nullable()->constrained('ims_vehicle_make')->onDelete('cascade');
            $table->foreignId('ims_vehicle_model_id')->nullable()->constrained('ims_vehicle_model')->onDelete('cascade');
            $table->foreignId('ims_vehicle_colour_id')->nullable()->constrained('ims_vehicle_colour')->onDelete('cascade');
            $table->foreignId('ims_vehicle_body_type_id')->nullable()->constrained('ims_vehicle_body_type')->onDelete('cascade');
            $table->foreignId('sa')->nullable()->constrained('hrms_staff')->onDelete('cascade');
            $table->foreignId('crms_people_marketing_info_id')->nullable()->constrained('crms_people_marketing_info')->onDelete('cascade');
            
            $table->string('status');
            $table->string('branch');
            $table->string('doc_no');
            $table->date('doc_date');
            $table->string('contact');
            $table->string('address');
            $table->string('vehicle_reg_no');
            $table->string('year_make');
            $table->string('body_size');
            $table->string('displacement');
            $table->string('max_output');
            $table->string('gross_vehicle_weight');
            $table->string('free_service');
            $table->string('warranty_period');
            $table->string('estimated_delivery');
            $table->text('special_modification');
            $table->text('remark_to_quotation');
            $table->text('sa_personal_remark');
            
            // Pricing & Accessories
            $table->string('selling_price_c/w_tax_freight_with_std_accessories');
            $table->string('number_plate');
            $table->string('bed_liner');
            $table->string('rollbar');
            $table->string('bug_protector');
            $table->string('door_visor');
            $table->string('side_step');
            $table->string('spare_tyre_lock');
            $table->string('alarm_centre_lock');
            
            $table->timestamps();
        });

        Schema::create('crms_people_booking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crms_people_quotation_id')->nullable()->constrained('crms_people_quotation')->onDelete('cascade');
            
            $table->string('status');
            $table->string('staff_remark')->nullable();
            $table->date('status_date')->nullable();
            $table->string('vso_no')->nullable();
            $table->date('booking_date')->nullable();
            $table->date('lap_date')->nullable();
            $table->string('proposed_inv')->nullable();
            $table->string('proposed_bank_loan_ammount')->nullable();
            $table->string('proposed_tenure')->nullable();
            $table->string('proposed_bank')->nullable();
            $table->string('assigned_to')->nullable();
            $table->timestamps();
        });

        Schema::create('crms_people_follow_up', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crms_people_booking_id')->nullable()->constrained('crms_people_booking')->nullOnDelete();
            $table->foreignId('crms_people_id')->nullable()->constrained('crms_people')->onDelete('cascade');
            $table->foreignId('ims_vehicle_make_id')->nullable()->constrained('ims_vehicle_make')->onDelete('cascade');
            $table->foreignId('ims_vehicle_model_id')->nullable()->constrained('ims_vehicle_model')->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('hrms_staff')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('hrms_staff')->nullOnDelete();
            
            $table->string('status'); // active, closed
            $table->date('date');
            $table->string('prospect_channel');
            $table->string('type')->nullable(); // new, recon, rebuild, used.
            $table->string('registration_no');
            $table->string('body')->nullable();
            $table->string('colour')->nullable();
            $table->string('customer_feedback')->nullable();
            $table->string('next_action')->nullable();
            $table->date('next_follow_up_date');
            $table->string('potential')->nullable();
            $table->string('manager_comment')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
        });
        
        Schema::create('crms_people_visiting_record', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crms_people_marketing_info_id')
                ->nullable()
                ->constrained('crms_people_marketing_info')
                ->onDelete('cascade')
                ->name('crms_visiting_info_fk'); // A custom, shorter name
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crms_people_visiting_record');
        Schema::dropIfExists('crms_people_follow_up');
        Schema::dropIfExists('crms_people_booking');
        Schema::dropIfExists('crms_people_quotation');
        Schema::dropIfExists('crms_people_marketing_info');
        Schema::dropIfExists('crms_vehicle_info');
        Schema::dropIfExists('crms_people');
        Schema::dropIfExists('crms_company');
        Schema::dropIfExists('crms_people_income');
        Schema::dropIfExists('crms_business_nature');
        Schema::dropIfExists('crms_people_occupation');
        Schema::dropIfExists('crms_people_race');
    }
};