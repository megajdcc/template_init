<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    use HasFactory;

    protected $fillable = [
    	'paypal_id',
    	'paypal_secret',
    	'production_paypal',
    	'marca_agua',
    ];


    protected $casts = [
    	'production_paypal' => 'boolean',

    ];
}
