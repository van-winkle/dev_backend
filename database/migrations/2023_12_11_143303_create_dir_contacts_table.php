<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /* Hola */
    public function up(): void
    {
        Schema::create('dir_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            $table->timestamp('created_at', 0)->useCurrent();
            $table->timestamp('updated_at', 0)->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dir_contacts');
    }
};
