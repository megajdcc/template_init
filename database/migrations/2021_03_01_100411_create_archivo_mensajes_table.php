<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchivoMensajesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archivo_mensajes', function (Blueprint $table) {
            $table->id();
            $table->string('archivo');
            $table->unsignedBigInteger('mensaje_id');
            $table->foreign('mensaje_id')->references('id')->on('mensajes')->onUpdate('cascade')->onDelete('cascade');
            $table->string('tipo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('archivo_mensajes');
    }
}
