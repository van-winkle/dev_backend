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
        Schema::create('pho_phone_incidents', function (Blueprint $table) {
            $table->id();

            $table->string('description');
            $table->float('percentage')->default(0);
            $table->string('resolution');
            $table->decimal('paymentDifference', 6, 2, true);
            $table->date('date_incident');
            $table->date('date_resolution');
            $table->string('state')->default('En Proceso');

            $table->unsignedBigInteger('adm_employee_id');
            $table->foreign('adm_employee_id')->references('id')->on('adm_employees');

            $table->unsignedBigInteger('pho_phone_supervisor_id');
            $table->foreign('pho_phone_supervisor_id')->references('id')->on('adm_employees');

            $table->unsignedBigInteger('pho_phone_id');
            $table->foreign('pho_phone_id')->references('id')->on('pho_phones');

            $table->unsignedBigInteger('pho_phone_incident_category_id');
            $table->foreign('pho_phone_incident_category_id')->references('id')->on('pho_phone_incident_categories');

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
        Schema::dropIfExists('pho_phone_incidents');
    }
};
