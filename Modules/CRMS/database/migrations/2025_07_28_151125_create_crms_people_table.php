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
        Schema::create('crms_corporate_group', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
        });

        Schema::create('crms_people_race', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
        });
        Schema::create('crms_people_occupation', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
        });

        Schema::create('crms_business_nature', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
        });

        Schema::create('crms_people_income', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
        });

        Schema::create('crms_people', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branch')->onDelete('cascade');
            $table->string('status')->nullable();
            $table->boolean('under_company_registration')->nullable();
            $table->string('customer_name');
            $table->string('company_name')->nullable();
            $table->foreignId('crms_corporate_group_id')->nullable()->constrained('crms_corporate_group')->onDelete('cascade');
            $table->string('id_type')->nullable();
            $table->string('id_number')->nullable();
            $table->string('tin')->nullable();
            $table->string('sst_reg_no');
            $table->string('gst_reg_no');
            $table->foreignId('hrms_staff_id')->nullable()->constrained('hrms_staff')->onDelete('cascade');
            $table->string('office_no')->nullable();
            $table->string('home_no')->nullable();
            $table->string('phone_no');
            $table->string('user_name')->nullable();
            $table->string('user_phone_no')->nullable();
            $table->string('fax_no')->nullable();
            $table->string('email')->nullable();
            $table->string('postal_address')->nullable();
            $table->string('postal_postcode')->nullable();
            $table->string('postal_city')->nullable();
            $table->string('postal_state')->nullable();
            $table->string('postal_country')->nullable();
            $table->string('primary_address');
            $table->string('primary_postcode');
            $table->string('primary_city');
            $table->string('primary_state');
            $table->string('primary_country');
            $table->string('zone')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->date('dob')->nullable();
            $table->foreignId('crms_people_race_id')->nullable()->constrained('crms_people_race')->onDelete('cascade');
            $table->string('religion')->nullable();
            $table->foreignId('crms_people_income_id')->nullable()->constrained('crms_people_income')->onDelete('cascade');
            $table->string('marital_status')->nullable();
            $table->string('company_size')->nullable();
            $table->string('sector')->nullable();
            $table->foreignId('crms_business_nature_id')->nullable()->constrained('crms_business_nature')->onDelete('cascade');
            $table->foreignId('crms_people_occupation_id')->nullable()->constrained('crms_people_occupation')->onDelete('cascade');
            $table->string('grading')->nullable();
            $table->boolean('is_corporate')->default(false);
            $table->text('lifestyle_interest')->nullable();
            $table->date('last_contact_date')->nullable();
            $table->string('link_customer')->nullable();
            $table->string('link_customer_type')->nullable();
            $table->string('terms')->nullable();
            $table->string('price_scheme')->nullable();
            $table->text('notes')->nullable();
            $table->text('log')->nullable();
            $table->timestamps();
        });

        Schema::create('crms_people_vehicle_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branch')->onDelete('cascade');
            $table->foreignId('crms_people_id')->nullable()->constrained('crms_people')->onDelete('cascade');
            $table->string('registration_no')->nullable();
            $table->string('stock_no')->nullable();
            $table->date('date_in')->nullable();
            $table->date('date_to_hq')->nullable();
            $table->string('colour')->nullable();
            $table->string('accesories_std')->nullable();
            $table->string('accesories_opt')->nullable();
            $table->string('body')->nullable();
            $table->string('chassis_no')->nullable();
            $table->string('engine_no')->nullable();
            $table->string('manufacture_year')->nullable();
            $table->foreignId('ims_vehicle_make_id')->nullable()->constrained('ims_vehicle_make')->onDelete('cascade');
            $table->foreignId('ims_vehicle_model_id')->nullable()->constrained('ims_vehicle_model')->onDelete('cascade');
            $table->string('type')->nullable();
            $table->string('rec_net_sp')->nullable();
            $table->string('accesories_otrCost')->nullable();
            $table->string('rec_otr_sp_stdAcc')->nullable();
            $table->text('notes')->nullable();
        });

        Schema::create('crms_people_follow_up', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crms_people_id')->nullable()->constrained('crms_people')->onDelete('cascade');
            $table->date('date');
            $table->string('type_of_prospect');
            $table->string('type');
            $table->string('vehicle_reg_no');
            $table->foreignId('ims_vehicle_make_id')->nullable()->constrained('ims_vehicle_make')->onDelete('cascade');
            $table->foreignId('ims_vehicle_model_id')->nullable()->constrained('ims_vehicle_model')->onDelete('cascade');
            $table->foreignId('ims_vehicle_body_type_id')->nullable()->constrained('ims_vehicle_body_type')->onDelete('cascade');
            $table->foreignId('ims_vehicle_colour_id')->nullable()->constrained('ims_vehicle_colour')->onDelete('cascade');
            $table->string('customer_feedback');
            $table->string('next_action');
            $table->date('next_follow_up_date');
            $table->string('potential');
            $table->string('manager_comment');
            $table->string('notes');
        });
        Schema::create('crms_people_booking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crms_people_follow_up_id')->nullable()->constrained('crms_people_follow_up')->onDelete('cascade');
            $table->string('status');
            $table->string('sa_remark');
            $table->foreignId('branch_id')->nullable()->constrained('branch')->onDelete('cascade');
            $table->foreignId('hrms_staff_id')->nullable()->constrained('hrms_staff')->onDelete('cascade');
            $table->string('type');
            $table->foreignId('ims_vehicle_make_id')->nullable()->constrained('ims_vehicle_make')->onDelete('cascade');
            $table->foreignId('ims_vehicle_model_id')->nullable()->constrained('ims_vehicle_model')->onDelete('cascade');
            $table->foreignId('ims_vehicle_colour_id')->nullable()->constrained('ims_vehicle_colour')->onDelete('cascade');
            $table->foreignId('ims_vehicle_body_type_id')->nullable()->constrained('ims_vehicle_body_type')->onDelete('cascade');
            $table->string('remark')->nullable();
            $table->date('status_date')->nullable();
            $table->string('quotation')->nullable();
            $table->string('vso_no')->nullable();
            $table->date('booking_date')->nullable();
            $table->date('lap_date')->nullable();
            $table->string('proposed_inv')->nullable();
            $table->string('proposed_bank_loan_ammount')->nullable();
            $table->string('proposed_tenure')->nullable();
            $table->string('proposed_bank')->nullable();
            $table->string('assigned_to')->nullable();
        });
        Schema::create('crms_people_marketing_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crms_people_id')->nullable()->constrained('crms_people')->onDelete('cascade');
            $table->string('other_related_business');
            $table->string('business_current_future');
            $table->string('repurchase_timing');
        });
        Schema::create('crms_people_visiting_record', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crms_people_marketing_info_id')
                ->nullable()
                ->constrained('crms_people_marketing_info', 'id')
                ->onDelete('cascade')
                ->index()
                ->name('fk_visiting_marketing'); // 👈 Custom short name
        });


        Schema::create('crms_quotation', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('branch');
            $table->string('doc_no');
            $table->date('doc_date');
            $table->foreignId('crms_people_id')->nullable()->constrained('crms_people')->onDelete('cascade');
            $table->string('contact');
            $table->string('address');
            $table->string('vehicle_reg_no');
            $table->foreignId('ims_vehicle_make_id')->nullable()->constrained('ims_vehicle_make')->onDelete('cascade');
            $table->foreignId('ims_vehicle_model_id')->nullable()->constrained('ims_vehicle_model')->onDelete('cascade');
            $table->foreignId('ims_vehicle_colour_id')->nullable()->constrained('ims_vehicle_colour')->onDelete('cascade');
            $table->foreignId('ims_vehicle_body_type_id')->nullable()->constrained('ims_vehicle_body_type')->onDelete('cascade');
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
            $table->foreignId('sa')->nullable()->constrained('hrms_staff')->onDelete('cascade');
            $table->text('sa_personal_remark');
            $table->foreignId('crms_people_marketing_info_id')->nullable()->constrained('crms_people_marketing_info')->onDelete('cascade');
            // A.
            $table->string('selling_price_c/w_tax_freight_with_std_accessories');
            // B.
            $table->string('number_plate');
            $table->string('bed_liner');
            $table->string('rollbar');
            $table->string('bug_protector');
            $table->string('door_visor');
            $table->string('side_step');
            $table->string('spare_tyre_lock');
            $table->string('alarm_centre_lock');
            // C.
            // $table->string('registration_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crms_quotation');
        Schema::dropIfExists('crms_people_visiting_record');
        Schema::dropIfExists('crms_people_marketing_info');
        Schema::dropIfExists('crms_people_booking');
        Schema::dropIfExists('crms_people_follow_up');
        Schema::dropIfExists('crms_people_vehicle_info');
        Schema::dropIfExists('crms_people');
        Schema::dropIfExists('crms_people_income');
        Schema::dropIfExists('crms_business_nature');
        Schema::dropIfExists('crms_people_occupation');
        Schema::dropIfExists('crms_people_race');
        Schema::dropIfExists('crms_corporate_group');
    }
};
