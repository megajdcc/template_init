<?php

use Illuminate\Support\Facades\Broadcast;

use App\Models\{User,Chat};

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


// Observaciones Channel public, pero solo para los Administradores 
broadcast::channel('observacion-module',function($user){
	return true;
});

Broadcast::channel('user-conected.{canal}',function($user, $canal){
	return ('chat' == $canal);
});


// Chat del sistema
Broadcast::channel('chat.{chat}',function($user, Chat $chat){	
	if($chat->usuarios->where('id',$user->id)){
		return [
			'id' => $user->id,
			'nombre'=> $user->nombre,
			'apellido' => $user->apellido,
			'usuario' => $user,
		];


	}
});



Broadcast::channel('nuevo-chat.{usuario}',function($user, User $usuario){
	return ((int) $user->id === (int) $usuario->id);
});