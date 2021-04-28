<?php

namespace App\Listeners;

use App\Events\ChatMensaje;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\NuevoMensaje;
use Illuminate\Support\Facades\Notification;

class MensajeUsuario implements ShouldQueue
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
     * @param  ChatMensaje  $event
     * @return void
     */
    public function handle(ChatMensaje $event)
    {
        $usuarios = $event->mensaje->chat->usuarios->where('id','!=',$event->mensaje->user_id)->where('conectado', false);

        if($usuarios->count() > 0 ){
             Notification::send($usuarios, new NuevoMensaje($event->mensaje));
        }
       
    }
}
