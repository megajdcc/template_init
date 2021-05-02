<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
    	'nombre',
    	'usuario_id',

    ];


   /**
    * Un album puede ser creado por un Solo Usuario
    * @return [App\Models\User] [EL usuario quien creo el Album]
    */
   public function publicante(){
    	return $this->belongsTo('App\Models\User','usuario_id','id');  
   }

   /**
    * Un ALbum puede tener cero o muchas fotos
    * @return [Illuinate\Database\Eloquent\Collection] [Una collection vacia o con muchas fotos que pertenezcan al album.]
    */
   public function fotos(){
   	return $this->hasMany('App\Models\Foto','album_id','id');
   }

}
