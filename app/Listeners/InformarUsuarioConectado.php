<?php

namespace App\Listeners;

use App\Events\UsuarioConectado;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class InformarUsuarioConectado implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UsuarioConectado  $event
     * @return void
     */
    public function handle(UsuarioConectado $event)
    {
        
        // $usuario = $event->usuario;
        // $usuario->conectado = true;
        // $usuario->save();

        return false;


    }
}
