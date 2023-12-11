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
            $table->integer('mobile_data');
            $table->integer('roaming_data');
            $table->integer('minutes');
            $table->integer('roaming_minutes');
            $table->boolean('active')->default(true);
            $table->string('type');
            $table->timestamps();
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
