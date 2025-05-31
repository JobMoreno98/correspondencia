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
        Schema::create('oficios', function (Blueprint $table) {
            $table->id();
            $table->string('num_oficio', 200);
            $table->date('fecha_oficio');
            $table->date('fecha_registro');
            $table->date('fecha_vencimiento');
            $table->enum('tipo', ['recibido', 'enviado']);
            $table->unsignedBigInteger('envia_id')->nullable();

            $table->foreign('envia_id')->references('id')->on('destinatarios');

            $table->unsignedBigInteger('turna_id')->nullable();

            $table->foreign('turna_id')->references('id')->on('destinatarios');
            $table->string('asunto');
            $table->string('observaciones');
            $table->string('archivado');
            $table->enum('estatus', ['sin asignar', 'asignado', 'concluido']);
            $table->string('archivo');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oficios');
    }
};
