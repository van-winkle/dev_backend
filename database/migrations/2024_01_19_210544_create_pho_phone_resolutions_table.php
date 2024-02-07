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
            
            $table->string('title');
            $table->string('reply');
            $table->date('date_response');

            $table->unsignedBigInteger('pho_phone_incident_id');
            $table->foreign('pho_phone_incident_id')->references('id')->on('pho_phone_incidents');

            $table->unsignedBigInteger('adm_employee_id');
            $table->foreign('adm_employee_id')->references('id')->on('adm_employees');

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
