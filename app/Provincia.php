<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    protected $fillable = ['idDpto','nombre','estado'];
    public $timestamps = false;
}
