<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foto extends Model
{
    use HasFactory;

    protected $fillable = [
    	'nombre',
    	'foto_con_marca',
    	'album_id',
        'precio'
    ];

    /**
     * Una foto pertenece solo a un Album de Foto
     * @return [App\Models\album] [El Album Object al que pertenece la foto]
     */
    public function album(){
    	return $this->belongsTo('App\Models\Album','album_id','id');
    }


    
}
