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
        Schema::create('pho_phone_incident_supervisors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('adm_employee_id')->nullable();
            $table->foreign('adm_employee_id')->references('id')->on('adm_employees');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pho_phone_incident_supervisors');
    }
};
