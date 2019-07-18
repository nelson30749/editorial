<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cuota extends Model
{
    protected $filliable = ['idPlan','fecha','monto',
    'estado'];
    public $timestamps = false;
}
