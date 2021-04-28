<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Models\User;

class UsuarioConectado implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $usuario;
    

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {   

        return new PresenceChannel('user-conected.chat');
    }


    public function broadcastWith(){

        return ['id' => $this->usuario->id,
                'nombre'      => $this->usuario->nombre.' '.$this->usuario->apellido,
                'rol'         => $this->usuario->rol->name,
            ];

    }
}
