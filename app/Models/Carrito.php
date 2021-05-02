<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    use HasFactory;

    protected $fillable = [
    	'usuario_id',
    	'foto_id',
    ];

    /**
     * Un Carrito pertenece solo a un usuario
     * @return [App\Models\User] [El usuario dueno de este carrito de compra]
     */
    public function usuario(){
    	return $this->belongsTo('App\Models\User','usuario_id','id');
    }

    /**
     * Un carrito solo puede contener Foto para comprar 
     * @return [App\Models\Foto] [La Foto]
     */
    public function foto(){
    	return $this->belongsTo('App\Models\FOto','foto_id','id');
    }



}
