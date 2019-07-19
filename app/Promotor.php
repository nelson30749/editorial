<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promotor extends Model
{
    protected $table= "promotores";
    protected $fillable = ['nombre','apellido','ci','direccion','telefono','email','estado'];
    public $timestamps = false;
}
