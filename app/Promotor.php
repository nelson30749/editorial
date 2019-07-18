<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promotor extends Model
{
    protected $table= "promotores";
    protected $fillable = ['nombres','apellidos','ci','direccion','telefono','email','estado'];
}
