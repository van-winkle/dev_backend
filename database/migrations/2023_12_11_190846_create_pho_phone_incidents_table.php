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
            $table->string('file_name')->nullable();
            $table->string('file_name_original')->nullable();
            $table->string('file_mimetype')->nullable();
            $table->string('file_size')->nullable();
            $table->string('file_path')->nullable();

            $table->double('percentage', 5, 2);

            $table->unsignedBigInteger('pho_phone_id');
            $table->foreign('pho_phone_id')->references('id')->on('pho_phones');

            $table->unsignedBigInteger('pho_phone_incident_category_id');
            $table->foreign('pho_phone_incident_category_id')->references('id')->on('pho_phone_incidents');

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
