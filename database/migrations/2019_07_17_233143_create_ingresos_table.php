<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIngresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingresos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedinteger('idProveedor');
            $table->dateTime('fecha');
            $table->integer('cantidad');
            $table->decimal('montoTotal',20,2);
            $table->boolean('estado')->default(1);  
            $table->foreign('idProveedor')->references('id')->on('proveedores');          
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
        Schema::dropIfExists('ingresos');
    }
}
