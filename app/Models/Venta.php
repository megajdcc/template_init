<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
    	'monto',
    	'fotos',
    	'pagado',
    	'respon_paypal',
    	'condicion_envio',
    	'email_envio',
    	'comprador_id',
    	'url_zip',
    ];


    protected $attributes = [
    	'condicion_envio' => 1, // 1 => Enviar por Email, 2 => Descargar despues de la compra
    	'pagado' => false,
    ];


    protected $casts = [
    	'pagado' => 'boolean',
    ];

    /**
     * Una venta se le hace a un Usuario Comprador
     * @return [App\Models\User] [EL usuario quien ha comprado algunas fotos]
     */
    public function comprador(){
    	return $this->belongsTo('App\Models\User','comprador_id','id');
    }


}
