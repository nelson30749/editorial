<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cuota extends Model
{
    protected $filliable = ['nro','idPlan','fecha','monto','estado'];
    public $timestamps = false;
}
