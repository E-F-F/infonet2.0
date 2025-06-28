<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ims_supplier', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_name');
            $table->string('supplier_office_number')->nullable();
            $table->string('supplier_email')->nullable();
            $table->string('supplier_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ims_supplier');
    }
};
