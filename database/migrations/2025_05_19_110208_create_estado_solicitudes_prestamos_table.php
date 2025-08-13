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
        Schema::create('estado_solicitudes_prestamos', function (Blueprint $table) {
            $table->id();
            $table->String('nombre');
            $table->foreignId('id_tipo_estado')->constrained('tipo_estados')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estado_solicitudes_prestamos');
    }
};
