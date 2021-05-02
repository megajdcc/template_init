<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

use App\Models\Album;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'apellido',
        'telefono',
        'fecha_nacimiento',
        'imagen',
        'direccion',
        'email',
        'password',
        'rol_id',
        'albumes',
        'is_password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_password' => 'boolean',
    ];


      public function getUrlAvatar(){

            if(is_null($this->imagen)){
                return asset('/storage/img-perfil/default.jpg');
            }else{
                return asset('/storage/img-perfil/'.$this->imagen);
            }
        
        // return app('url').'/storage/img-perfil/'.$this->imagen;
        
    }



    public function rol(){
        return $this->belongsTo('App\Models\Rol','rol_id','id');
    }


     /**
     * Un usuario puede estar en ninguno o muchos chats 
     * @return [Illuminate\Database\Eloquent\Colleption] [Una colleption de chats o una colleption vacia si no tiene ninguno]
     */
    public function chats(){
        return $this->belongsToMany('App\Models\Chat','user_chat','user_id','chat_id');
    }


    public function getAvatar(){
        
        if(empty($this->imagen)){
            $this->imagen = 'avatar_masculino.jpg';

            return asset('storage/img-perfil/'.$this->imagen);
        }else{
             return asset('storage/img-perfil/'.$this->imagen);
        }

    }


    /**
     * UN Usuario puede agregar cero o muchas Observaciones
     * @return [Illuminate\Database\Eloquent\Collection] [Collection vacia p con muchas Observacions]
     */
    public function observaciones(){
        return $this->hasMany('App\Models\Observacion','usuario_id','id');
    }


    public function getNombreCompleto(){
       return $this->nombre . ' '. $this->apellido;
    }


    /**
     * Un Usuario(Administrador del sistema) puede tener muchos Albumes que hayan sido creado por el .
     */
    public function Albumes(){
        return $this->hasMany('App\Models\Album','usuario_id','id');
    }
    
    public function AlbumesAsignados(){
        $albumes = json_decode($this->albumes);
        $albumes = Album::whereIn('id',$albumes)->get();
        return $albumes;
    }


    /**
     * Un usuario puede tener muchas fotos en el carrito de compra o ninguna
     * @return [Illuminate\Database\Eloquent\Collection] [Una Collection de fotos que el usuario haya elegido...]
     */
    public function fotoInCarrito(){
        return $this->belongsToMany('App\Models\Foto','carritos','usuario_id','foto_id')->withPivot(['created_at']);
    }

    /**
     * Un usuario puede comprar muchas fotos...
     * @return [Illuminate\Database\Eloquent\Collection] [Una collection de Ventas que le hayan hecho a este usuario]
     */
    public function compras(){
        return $this->hasMany('App\Models\Venta','comprador_id','id');
    }

}
