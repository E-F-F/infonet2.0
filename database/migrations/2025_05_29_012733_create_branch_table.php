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
        Schema::create('branch', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_of')->nullable()->constrained('company')->onDelete('cascade');
            $table->string('name');
            $table->string('code');
            $table->text('address')->nullable();
            $table->string('print_name')->nullable();
            $table->string('company_reg_no')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('work_minutes_per_day')->nullable();
            $table->string('epf_employer_no')->nullable();
            $table->string('contact_person_name')->nullable();
            $table->string('contact_phone_no')->nullable();
            $table->string('socso_employer_no')->nullable();
            $table->string('lhdn_employer_no')->nullable();
            $table->string('hrdp_no')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('bank', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('description');
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch');
        Schema::dropIfExists('bank');
    }
};
