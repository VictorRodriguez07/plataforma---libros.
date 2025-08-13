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
        Schema::create('solicitud_prestamo_libros', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_solicitud');
            $table->string('direccion_envio')->nullable();
            $table->int('dias_solicitados')->nullable();
            $table->foreignId('id_usuario')->constrained('user')->onDelete('cascade');
            $table->foreignId('id_libro')->constrained('libros')->onDelete('cascade');
            $table->foreignId('id_estado')->constrained('estados_solicitudes_prestamos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_prestamo_libros');
    }
};
