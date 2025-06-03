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
        Schema::dropIfExists('hrms_event');
    }
};
