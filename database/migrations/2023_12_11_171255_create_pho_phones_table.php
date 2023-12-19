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
        Schema::create('pho_phones', function (Blueprint $table) {
            $table->id();

            $table->string('number', 9);
            $table->string('type', 50);
            $table->string('imei', 15);
            $table->decimal('price', 6, 2, true);

            $table->boolean('active')->default(true);

            $table->unsignedBigInteger('adm_employee_id')->nullable();
            $table->foreign('adm_employee_id')->references('id')->on('adm_employees');
            $table->unsignedBigInteger('pho_phone_plan_id')->nullable();
            $table->foreign('pho_phone_plan_id')->references('id')->on('pho_phone_plans');
            $table->unsignedBigInteger('pho_phone_contract_id');
            $table->foreign('pho_phone_contract_id')->references('id')->on('pho_phone_contracts');
            $table->unsignedBigInteger('pho_phone_model_id');
            $table->foreign('pho_phone_model_id')->references('id')->on('pho_phone_models');

            $table->timestamp('created_at', 0)->useCurrent();
            $table->timestamp('updated_at', 0)->useCurrent();
            $table->softDeletes();

            $table->unique(['number','deleted_at']);
            $table->unique(['imei','deleted_at']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pho_phones');
    }
};
