<?php

use App\Models\General\GralConfiguration;
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
        Schema::create('gral_configurations', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('identifier');
            $table->text('value');

            $table->timestamp('created_at', 0)->useCurrent();
            $table->timestamp('updated_at', 0)->useCurrent();
            $table->softDeletes();
        });

        GralConfiguration::insert([
            [
                'name' => 'Minutos de Cortesía',
                'identifier' => 'minutos_cortesia',
                'value' => 10,
            ],
            [
                'name' => 'Dias para aplicar incidencias',
                'identifier' => 'incidence_day',
                'value' => 60,
            ],
            [
                'name' => 'Encargados de asignadores de Teléfonos',
                'identifier' => 'phone_admin',
                'value' => '22,167,1',
            ],
            [
                'name' => 'Asignadores de Teléfonos',
                'identifier' => 'phone_supervisor',
                'value' => '22,167,2',
            ],
            [
                'name' => 'Supervisores de incientes',
                'identifier' => 'incidence_supervisor',
                'value' => '22,167,1',
            ]
        ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gral_configurations');
    }
};
