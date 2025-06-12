<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('incident_reports', function (Blueprint $table) {
            $table->id();
            $table->string('ticket');
            $table->unsignedTinyInteger('channel_id')->nullable();
            $table->string('category')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedTinyInteger('status')->nullable();
            $table->unsignedTinyInteger('call_type')->nullable();
            $table->unsignedBigInteger('caller_id')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone_unmask')->nullable();
            $table->string('voip_number')->nullable();
            $table->string('caller')->nullable();
            $table->string('created_by')->nullable();
            $table->text('address')->nullable();
            $table->string('location')->nullable();
            $table->string('district_id')->nullable();
            $table->string('district')->nullable();
            $table->string('subdistrict_id')->nullable();
            $table->string('subdistrict')->nullable();
            $table->text('notes')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('incident_created_at')->nullable();
            $table->timestamp('incident_updated_at')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_reports');
    }
};
