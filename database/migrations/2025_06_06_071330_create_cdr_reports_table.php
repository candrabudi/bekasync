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
        Schema::create('cdr_reports', function (Blueprint $table) {
            $table->id();
            $table->string('ticket')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone_unmask')->nullable();
            $table->string('voip_number')->nullable();
            $table->dateTime('datetime_entry_queue')->nullable();
            $table->integer('duration_wait')->nullable();
            $table->dateTime('datetime_init')->nullable();
            $table->dateTime('datetime_end')->nullable();
            $table->integer('duration')->nullable();
            $table->string('status')->nullable();
            $table->string('uniqueid')->unique();
            $table->string('extension')->nullable();
            $table->string('agent_name')->nullable();
            $table->text('recording_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cdr_reports');
    }
};
