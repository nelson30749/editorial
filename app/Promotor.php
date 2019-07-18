<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promotor extends Model
{
    protected $fillable = ['nombres','apellidos','ci','direccion','telefono','email','estado'];
}
