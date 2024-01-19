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
        Schema::create('pho_phone_resolutions', function (Blueprint $table) {
            $table->id();
            $table->string('reply');
            $table->date('date_resolution');

            $table->unsignedBigInteger('pho_phone_incident_id');
            $table->foreign('pho_phone_incident_id')->references('id')->on('pho_phone_incidents');


            $table->unsignedBigInteger('pho_phone_incident_supervisor_id');
            $table->foreign('pho_phone_incident_supervisor_id')->references('id')->on('pho_phone_incident_supervisors');

            $table->timestamp('created_at', 0)->useCurrent();
            $table->timestamp('updated_at', 0)->useCurrent();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pho_phone_incident_resolutions');
    }
};
