<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleEntregasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_entregas', function (Blueprint $table) {
            $table->unsignedinteger('idEntrega');
            $table->unsignedinteger('idLibro');
            $table->integer('cantidad');
            $table->decimal('precio',20,2);
            $table->boolean('estado')->default(1);
            $table->primary(['idEntrega','idLibro']);
            $table->foreign('idEntrega')->references('id')->on('entregas');
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
        Schema::dropIfExists('detalle_entregas');
    }
}
