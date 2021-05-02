<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->decimal('monto');
            $table->json('fotos');
            $table->boolean('pagado')->default(false);
            $table->json('respon_paypal')->nullable();
            $table->smallInteger('condicion_envio')->default(1);
            $table->string('email_envio')->nullable();
            $table->unsignedBigInteger('comprador_id');
            $table->foreign('comprador_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->text('url_zip')->nullable();
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
        Schema::dropIfExists('ventas');
    }
}
