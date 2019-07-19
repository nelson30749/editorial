<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_pagos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedinteger('idEntrega');
            $table->datetime('fecha');
            $table->date('fechaInicio');
            $table->date('fechaFin');
            $table->string('pago', 50);
            $table->decimal('montoTotal' ,20 ,2);
            $table->boolean('estado')->default(1);
            $table->foreign('idEntrega')->references('id')->on('entregas');
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
        Schema::dropIfExists('plan_pagos');
    }
}
