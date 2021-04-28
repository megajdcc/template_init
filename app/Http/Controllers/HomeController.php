<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\{Auth};

use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $usuario = Auth::user();
        
        $usuario->rol = $usuario->rol;
 
        if($usuario->roles->first()){
            $usuario->roles->first()->permissions;
        } 
        
        $usuario->avatar = $usuario->getAvatar();

        $chats = $usuario->chats;

        foreach ($chats as $key => $value) {
            
            foreach ($chats[$key]->mensajes  as $k => $v) {
                $chats[$key]->mensajes[$k]->usuario;
                $chats[$key]->mensajes[$k]->archivos; 
            }

            $chats[$key]->usuarios = $value->usuarios;
        }
  
        $usuario->chats = $chats;

        return view('home', compact('usuario'));

    }


    public function getDataApp(){

        $usuario = Auth::user();
        $usuario->rol = $usuario->rol;
        if($usuario->roles->first()){
            $usuario->roles->first()->permissions;
        } 
        
        $usuario->avatar = $usuario->getAvatar();

        $chats = $usuario->chats;

        foreach ($chats as $key => $value) {
            
            foreach ($chats[$key]->mensajes  as $k => $v) {
                $chats[$key]->mensajes[$k]->usuario;
                $chats[$key]->mensajes[$k]->archivos; 
            }

            $chats[$key]->usuarios = $value->usuarios;
        }
  
        $usuario->chats = $chats;

        return response()->json($usuario);
        
    }


    public function getCompaneros(){

        $companeros = User::get();

        foreach ($companeros as $key => $value) {
            $companeros[$key]->rol = $value->rol;
        }
 
        return response()->json($companeros);
        
    }






}
