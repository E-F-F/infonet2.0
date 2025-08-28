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
        Schema::create('hrms_event_participant', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hrms_event_id');
            $table->unsignedBigInteger('hrms_staff_id');
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('hrms_event_id')
                  ->references('id')->on('hrms_event')
                  ->onDelete('cascade');

            $table->foreign('hrms_staff_id')
                  ->references('id')->on('hrms_staff')
                  ->onDelete('cascade');

            $table->unique(['hrms_event_id', 'hrms_staff_id']); // prevent duplicate entries
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrms_event_participant');
    }
};
