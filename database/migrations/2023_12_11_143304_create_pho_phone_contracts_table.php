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
        Schema::create('pho_phone_contracts', function (Blueprint $table) {
            $table->id();
            
            $table->string('code');
            $table->date('start_date');
            $table->date('expiry_date');
            $table->boolean('active')->default(true);

            $table->unsignedBigInteger('dir_contact_id')->nullable();
            $table->foreign('dir_contact_id')->references('id')->on('dir_contacts');

            $table->timestamp('created_at', 0)->useCurrent();
            $table->timestamp('updated_at', 0)->useCurrent();
            $table->softDeletes();
            $table->unique(['code','deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pho_phone_contracts');
    }
};
