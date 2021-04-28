<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Models\Observacion;

class ObservacionModulo implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $observacion;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Observacion $observacion)
    {
        $this->observacion = $observacion;
        $this->observacion->usuario;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('observacion-module');
    }


    public function broadcastWith(){
        return [
            'observacion' => $this->observacion,
        ];
    }

}
