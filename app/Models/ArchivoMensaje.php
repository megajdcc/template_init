<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchivoMensaje extends Model
{
    use HasFactory;
    
    protected $fillable = [
    	'id',
    	'archivo',
    	'mensaje_id',
    	'tipo',// los tipos pueden ser => png/jpg , mp4, zip etc etc ... La extension del archivo
    ];


    /**
     * El mensaje al que pertenece este archivo 
     * @return [App\Models\Mensaje] [La instancia de Mensaje a la que pertenece este archivo]
     */
    public function mensaje(){
    	return $this->belongsTo('App\Models\Mensaje','mensaje_id','id');
    }
}
