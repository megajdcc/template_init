<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class UsuarioCreado extends Mailable implements ShouldQueue
{
   use Queueable;

    protected $usuario ; 
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $usuario){
        $this->usuario = $usuario;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        if(is_null($this->usuario->roles->first())){
            $rol_name =  'Sin Definir';
        }else{
             $rol_name =  ($this->usuario->roles->first())->name;
        }


        return $this->markdown('emails.usuario.creado')
                    ->subject('Bienvenido a InverAgro '.$this->usuario->nombre. ' ' . $this->usuario->apellido)
                    ->with([
                        'Nombre' => $this->usuario->nombre . ' ' . $this->usuario->apellido,
                        'Rol'    => $rol_name,
                        'Email'  => $this->usuario->email,
                        'Url'    => env('APP_URL').'/usuario/'.$this->usuario->id.'/establecer/contrasena',
                    ]);
                    
    }
}
