<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Observacion extends Model
{
    use HasFactory;



    protected $fillable = [
    	'modulo',
    	'usuario_id',
    	'comentario',
    	'adjunto',
    	'atendido'
    ];

    protected $casts = [
    	'atendido' => 'boolean',
    ];


    /**
     * Una Observacion la puede hacer y solo hacer, un usuario del sistema
     * @return [App\Models\User] [El Usuario Instancia que ha generado esta Observacion]
     */
    public function usuario(){	
    	return $this->belongsTo('App\Models\User','usuario_id','id');
    }

    

}
