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
        Schema::create('pho_phone_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('mobile_data')->nullable();
            $table->integer('roaming_data')->nullable();
            $table->integer('minutes')->nullable();
            $table->integer('roaming_minutes')->nullable();
            $table->boolean('active')->default(true);

            $table->unsignedBigInteger('pho_phone_contract_id');
            $table->foreign('pho_phone_contract_id')->references('id')->on('pho_phone_contracts');

            $table->unsignedBigInteger('pho_phone_type_phone_id');
            $table->foreign('pho_phone_type_phone_id')->references('id')->on('pho_phone_type_phones');

            $table->timestamp('created_at', 0)->useCurrent();
            $table->timestamp('updated_at', 0)->useCurrent();
            $table->softDeletes();

            $table->unique(['name','deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pho_phone_plans');
    }
};
