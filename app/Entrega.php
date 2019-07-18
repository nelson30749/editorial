<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    protected $fillable = ['nro','idPromotor','fecha','comprobante','cantidad','montoTotal','estado'];
    public $timestamps = false;

}
