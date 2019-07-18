<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    protected $fillable = ['nro','idProveedor','fecha','cantidad','montoTotal','estado'];
    public $timestamps = false;
}
