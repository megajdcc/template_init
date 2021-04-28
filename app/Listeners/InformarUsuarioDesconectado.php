<?php

namespace App\Listeners;

use App\Events\UsuarioDesconectado;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class InformarUsuarioDesconectado
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
     * @param  UsuarioDesconectado  $event
     * @return void
     */
    public function handle(UsuarioDesconectado $event)
    {
        // $usuario = $event->usuario;
        // // $usuario->conectado = false;
        // $usuario->save();
        return false;
    }
}
