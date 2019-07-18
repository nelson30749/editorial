<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ci' ,50);
            $table->string('nombre' ,50);
            $table->string('apellido' ,50);
            $table->string('direccion' ,80);
            $table->integer('telefono');
            $table->string('email' ,80);
            $tabler->boolean('estado')->default(1);
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
        Schema::dropIfExists('promotores');
    }
}
