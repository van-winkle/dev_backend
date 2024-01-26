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
        Schema::create('pho_phone_percentage_rules', function (Blueprint $table) {
            $table->id();
            $table->decimal('percentage_discount', 5, 2);

            $table->unsignedBigInteger('pho_phone_contract_id');
            $table->foreign('pho_phone_contract_id')->references('id')->on('pho_phone_contracts');

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
        Schema::dropIfExists('pho_phones_percentage_rules');
    }
};
