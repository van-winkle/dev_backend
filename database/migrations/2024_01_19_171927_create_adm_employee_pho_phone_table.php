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
        Schema::create('adm_employee_pho_phone', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('adm_employee_id');
            $table->unsignedBigInteger('pho_phone_id');

            $table->foreign('adm_employee_id')->references('id')->on('adm_employees');
            $table->foreign('pho_phone_id')->references('id')->on('pho_phones');

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
        Schema::dropIfExists('adm_employee_pho_phone');
    }
};
