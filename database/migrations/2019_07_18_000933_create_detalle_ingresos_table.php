<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleIngresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_ingresos', function (Blueprint $table) {
            $table->unsignedinteger('idIngreso');
            $table->unsignedinteger('idLibro');
            $table->integer('cantidad');
            $table->decimal('precio',20,2);
            $table->boolean('estado')->default(1);
            $table->primary(['idIngreso','idLibro']);
            $table->foreign('idIngreso')->references('id')->on('ingresos');
            $table->foreign('idLibro')->references('id')->on('libros');
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
        Schema::dropIfExists('detalle_ingresos');
    }
}
