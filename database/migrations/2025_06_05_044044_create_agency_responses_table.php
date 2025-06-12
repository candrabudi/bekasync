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
        Schema::create('agency_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('incident_report_id'); // define the column first
            $table->string('report_id');
            $table->string('ticket');
            $table->unsignedBigInteger('dinas_id')->nullable();
            $table->string('dinas')->nullable();
            $table->unsignedTinyInteger('status')->nullable();
            $table->timestamps();

            // $table->foreign('incident_report_id')
            //     ->references('id')->on('incident_reports')
            //     ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agency_responses');
    }
};
