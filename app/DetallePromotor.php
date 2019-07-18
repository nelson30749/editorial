<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetallePromotor extends Model
{
    protected $fillable = ['idPromotor','idProvincia','estado'];
    public $timestamps = false;
}
