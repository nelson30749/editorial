<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuotas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedinteger('idPlan');
            $table->integer('nro');            
            $table->datetime('fecha');
            $table->decimal('monto',11 ,2);
            $table->boolean('estado')->default(1);
            // $table->primary(['id','idPlan']);
            // $table->foreign('idCuota')->references('id')->on('coutas');
            $table->foreign('idPlan')->references('id')->on('plan_pagos');
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
        Schema::dropIfExists('cuotas');
    }
}
