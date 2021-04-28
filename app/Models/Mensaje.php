<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    use HasFactory;

     protected $fillable = [
    	'id',
    	'mensaje',
    	'user_id',
    	'chat_id',
    	'leido',
       'created_at'
    ];


    protected $attributes = [
    	'leido' => false,  
    ];

    protected $casts = [
    	'leido' => 'boolean',
    ];


    public function chat(){
    	return $this->belongsTo('App\Models\Chat','chat_id','id');
    }

    public function usuario(){
    	return $this->belongsTo('App\Models\User','user_id','id');
    }

    /**
     * UN mensaje puede que tenga uno o muchos archivos  
     * @return [Illuminate\Database\Eloquent\Colleption] [Una colleption de archivos que tenga o no este mensaje, de lo contrario una colleption vacia]
     */
    public function archivos(){
    	return $this->hasMany('App\Models\ArchivoMensaje','mensaje_id','id');
    }

}
