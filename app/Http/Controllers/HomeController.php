<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\{Auth};

use App\Models\{User,Venta,Foto,Album};

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
        $usuario->albumes = ($usuario->albumes) ? json_decode($usuario->albumes) : [];
        $usuario->fotoInCarrito;
        
        return response()->json($usuario);
        
    }


    public function getCompaneros(){

        $companeros = User::get();

        foreach ($companeros as $key => $value) {
            $companeros[$key]->rol = $value->rol;
        }
 
        return response()->json($companeros);
        
    }


    public function tableroAdmin(){

        $result = collect([
            'total_ventas' => Venta::get()->sum('monto'),
            'cantidad_fotos' => Foto::get()->count(),
            'cantidad_albums' => Album::get()->count(),
            'cantidad_usuarios' => User::get()->count()
        ]);

        return response()->json($result);
    }

     public function tableroUser(){

        $compras = Auth::user()->compras;


        $fotos_compradas = 0;

        foreach ($compras as $key => $compra) {
            $fotos = json_decode($compra->fotos);
            $fotos_compradas += count($fotos);
        }

        $result = collect([
            'total_compras' => Auth::user()->compras->count(),
            'fotos_compradas' => $fotos_compradas,
            'cantidad_albumes' => (Auth::user()->rol->name != 'Super Administrador') ? Auth::user()->AlbumesAsignados()->count() : 0,
        ]);

        return response()->json($result);
    }

}
