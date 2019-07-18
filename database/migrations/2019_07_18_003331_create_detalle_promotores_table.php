<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetallePromotoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_promotores', function (Blueprint $table) {
            $table->unsignedinteger('idPromotor');
            $table->unsignedinteger('idProvincia');
            $table->boolean('estado')->default(1);
            $table->primary(['idPromotor','idProvincia']);
            $table->foreign('idPromotor')->references('id')->on('promotores');
            $table->foreign('idProvincia')->references('id')->on('provincias');
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
        Schema::dropIfExists('detalle_promotores');
    }
}
