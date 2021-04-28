<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
   
    use HasFactory;


    protected $fillable = [
    	'id',
    ];


    /**
     * Un chat puede tener uno  o muchos usuarios 
     * @return [Illuminate\Database\eloquent\Colleption] [Una colleption de usuarios que estan afiliados al chat...]
     */
    public function usuarios(){
    	return $this->belongsToMany('App\Models\User','user_chat','chat_id','user_id');
    }


    /**
     * Un chat puede tener muchos mensajes o no 
     * @return [Illuminate\Database\Eloquent\Colleption] [Una Collepction de usuarios]
     */
    public function mensajes(){
    	return $this->hasMany('App\Models\Mensaje','chat_id','id');
    }

}
