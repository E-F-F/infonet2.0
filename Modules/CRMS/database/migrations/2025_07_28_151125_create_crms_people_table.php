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
        Schema::create('make_model', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
        });

        Schema::create('crms_people', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branch')->onDelete('cascade');
            $table->string('status')->nullable();
            $table->boolean('under_company_registration')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('company_name')->nullable();
            $table->foreignId('crms_corporate_group_id')->nullable()->constrained('crms_corporate_group')->onDelete('cascade');
            $table->string('id_type')->nullable();
            $table->string('id_number')->nullable();
            $table->string('tin')->nullable();
            $table->string('sst_reg_no');
            $table->foreignId('sa')->nullable()->constrained('hrms_staff')->onDelete('cascade');
            $table->string('telephone_o')->nullable();
            $table->string('telephone_h')->nullable();
            $table->string('owner_hp_no')->nullable();
            $table->string('user_name')->nullable();
            $table->string('user_hp_no')->nullable();
            $table->string('fax_no')->nullable();
            $table->string('email')->nullable();
            $table->string('postal_address')->nullable();
            $table->string('postal_postcode')->nullable();
            $table->string('postal_city')->nullable();
            $table->string('postal_state')->nullable();
            $table->string('postal_country')->nullable();
            $table->string('primary_address')->nullable();
            $table->string('primary_postcode')->nullable();
            $table->string('primary_city')->nullable();
            $table->string('primary_state')->nullable();
            $table->string('primary_country')->nullable();
            $table->string('zone')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->date('dob')->nullable();
            $table->foreignId('race')->nullable()->constrained('crms_people_race')->onDelete('cascade');
            $table->string('religion')->nullable();
            $table->foreignId('monthly_house_income')->nullable()->constrained('crms_people_income')->onDelete('cascade');
            $table->string('marital_status')->nullable();
            $table->string('company_size')->nullable();
            $table->string('sector')->nullable();
            $table->foreignId('nature_of_business')->nullable()->constrained('crms_business_nature')->onDelete('cascade');
            $table->foreignId('occupation')->nullable()->constrained('crms_people_occupation')->onDelete('cascade');
            $table->string('grading')->nullable();
            $table->boolean('is_corporate')->default(false);
            $table->text('lifestyle_interest')->nullable();
            $table->date('last_contact_date')->nullable();
            $table->string('link_customer')->nullable();
            $table->string('link_customer_type')->nullable();
            $table->string('terms')->nullable();
            $table->string('price_scheme')->nullable();
            $table->text('notes');
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
            $table->foreignId('make_model_id')->nullable()->constrained('make_model')->onDelete('cascade');
            $table->string('type')->nullable();
            $table->string('rec_net_sp')->nullable();
            $table->string('accesories_otrCost')->nullable();
            $table->string('rec_otr_sp_stdAcc')->nullable();
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crms_people');
    }
};
