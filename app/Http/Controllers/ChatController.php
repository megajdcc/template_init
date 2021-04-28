<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\{Chat,Mensaje};
use Illuminate\Support\Facades\{DB,Auth};
use App\Events\ChatMensaje;
use App\Events\NuevoChat;

class ChatController extends Controller
{
    

    public function store(Request $request, User $usuario){

            $datos = $request->validate([
                    'user_related' => 'required|exists:users,id',
            ],[
                    'user_related.required' => 'El chat se abre con dos personas como mínimo',
                    'user_related.exists' => 'El Usuario no existe intente nuevamente', 
            ]);


            try{
                DB::beginTransaction();
                    $chat = Chat::create();
                    $chat->usuarios()->attach($datos['user_related']);
                    $chat->usuarios()->attach($usuario->id);

                    $chat->usuarios;
                    $chat->mensajes;

                    broadcast(new NuevoChat($chat));

                DB::commit();
                $result = true;
            }catch(Exception $e){
                DB::rollBack();
                $result = false;
            }
            return response()->json(['success' => $result, 'chat' => ($result) ? $chat: null]);

    }



    public function sendMensaje(Request $request, Chat $chat){


        $datos = $request->validate([
            'mensaje' => 'required',
            'usuario_id' => 'required',
        ],[
            'mensaje.required' => 'El mensaje es importante no lo olvides',
            'usuario_id.required' => 'Necesito saber quien esta redactando el mensaje, por favor recargue el sistema y vuelva a intentarlo'
        ]);


        try{
            DB::beginTransaction();
                $mensaje = Mensaje::create([
                    'mensaje' => $datos['mensaje'],
                    'user_id' => $datos['usuario_id'],
                    'chat_id' => $chat->id,
                    'leido'   => false,
                ]);

                $mensaje->usuario;
                $mensaje->archivos;
                broadcast(new ChatMensaje($mensaje))->toOthers();

            DB::commit();
            $result = true;
        }catch(Exception $e){
            DB::rollBack();
            $result = false;
        }


        return response()->json(['success' => $result, 'mensaje' => ($result) ? $mensaje : null]);

    }


}
