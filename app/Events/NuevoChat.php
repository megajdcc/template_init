<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Chat;

use Illuminate\Support\Facades\Auth;

class NuevoChat implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    protected $chat;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Chat $chat)
    {
        $this->chat = $chat;
        $this->chat->usuarios;
        $this->chat->mensajes;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {   
        $channel = [];

        foreach($this->chat->usuarios as $key => $usuario){
            array_push($channel, new PrivateChannel('nuevo-chat.'.$usuario->id));
        }

        return $channel;
    }

    public function broadcastWith(){
        return [
            'chat' => $this->chat, 
        ];
    }

}
