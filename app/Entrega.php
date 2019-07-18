<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    protected $fillable = ['idPromotor','fecha','comprovante',
    'cantidad','montoTotal','estado'];
    public $timestamps = false;

}
