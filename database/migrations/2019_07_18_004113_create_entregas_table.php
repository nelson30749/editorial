<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntregasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entregas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('nro');
            $table->unsignedinteger('idPromotor');
            $table->datetime('fecha');
            $table->string('comprobante',200)->nullable();
            $table->integer('cantidad');
            $table->decimal('montoTotal');
            $table->integer('pago');
            $table->boolean('estado')->default(1);  
            $table->foreign('idPromotor')->references('id')->on('promotores');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entregas');
    }
}
