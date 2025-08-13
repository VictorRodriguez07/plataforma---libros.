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
        Schema::create('prestamo_libros', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_devolucion')->nullable();
            $table->foreignId('id_solicitud_prestamo')
                ->constrained('solicitudes_prestamos_libros')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestamo_libros');
    }
};
