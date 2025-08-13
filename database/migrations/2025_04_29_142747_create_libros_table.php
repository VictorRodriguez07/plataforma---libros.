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
        Schema::create('libros', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->date('fecha_publicacion');
            $table->string('autor');
            $table->text('sinopsis');
            $table->string('img');
            $table->int('cantidad');
            $table->unsignedInteger('genero_id');
            $table->foreign('genero_id')->references('id')->on('generos')->onDelete('cascade');
            $table->foreign('id_estado')->references('id')->on('estado_solicitudes_prestamos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libros');
    }
};
