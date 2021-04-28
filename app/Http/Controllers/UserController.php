<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;

Use App\Notifications\WelcomeUsuario;

use Illuminate\Support\Facades\{Hash,Auth,File,Storage,Validator,DB};

use Illuminate\Validation\Rule;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }


    // public function getUsuarios(string $nombre){

    //     $usuarios = User::where('nombre','LIKE',"%$nombre%")->get();

    //     return response()->json($usuarios);

    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('home');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try{
            DB::beginTransaction();
                $usuario = $this->crearUsuario($this->validarDatos($request));
                $usuario->notify(new WelcomeUsuario($usuario));

            DB::commit();
            $usuario->rol;
            $usuario->avatar = $usuario->getAvatar();
            if($usuario->roles->first()){
                $usuario->roles->first()->permissions;
            } 
            $chats = $usuario->chats;

            foreach ($chats as $key => $value) {
                $chats[$key]->mensajes = $value->mensajes;
                $chats[$key]->usuarios = $value->usuarios;
            }

            $usuario->chats = $chats;

             $result = true;

        }catch(Excelption $e){
            DB::rollBack();
            $result = false;
        }
        return response()->json(['success' => true,'usuario' => ($result) ? $usuario : null]);

    }


    /**
     * [crearUsuario description]
     * @param  Array  $datos [Los datos del nuevo usuario a crear ]
     * @return [App\User]        [El usuario creado]
     */
    public function crearUsuario(Array $datos) : User {

        $usuario = User::create([
            'nombre' => $datos['nombre'],
            'apellido'  => $datos['apellido'],
            'telefono'  => $datos['telefono'],
            'rol_id'    => $datos['rol_id'],
            'email'     => $datos['email'],
            'direccion' => $datos['direccion'],
            'password'  => Hash::make('20464273jd'),
            'fecha_nacimiento' => (isset($datos['fecha_nacimiento'])) ? $datos['fecha_nacimiento'] : null ,
        ]);

        $usuario->assignRole($datos['rol_id']);
        $usuario->save();

        return $usuario;}


    public function validarDatos(Request $request) : array{


        $datos = $request->validate([
            'nombre'           => 'required|string|min:2',
            'apellido'         => 'required|string|min:2',
            'telefono'         => 'required|unique:users,telefono',
            'rol_id'           => 'required|exists:roles,id',
            'email'            => 'required|email|unique:users,email',
            'direccion'        => 'nullable',
            'fecha_nacimiento' => 'nullable',

        ],[
            'nombre.required'          => 'El nombre es importante',
            'nombre.string'            => 'El nombre no es valido',
            'apellido.required'          => 'El apellido es importante',
            'apellido.string'            => 'El apellido no es valido',
            'apellido.min'               => 'El apellido debe tener al menos de dos caracteres',
            'nombre.min'               => 'El nombre debe tener al menos de dos caracteres',

            'telefono.required'             => 'Este campo es obligatorio',
            'telefono.unique'               => 'El teléfono ya lo esta utilizando otro usuario, debe ser único',
            'rol_id.required'                  => 'Este campo es obligatorio',
            'rol_id.exists'                    => 'El rol no existe verifique',
            'email.required'                => 'Este campo es obligatorio',
            'email.email'                   => 'El email no es valido por favor verifique',
            'email.unique'                  => 'El email debe ser único ya otro usuario lo esta usando.',

        ]);

          return $datos;}


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('home');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $usuario){ return view('home');}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\User $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $usuario){
        $datos = $request->validate([
            'nombre'    => 'required|string|min:2',
            'apellido'  => 'required|string|min:2',
            'telefono'  => ['bail','required',Rule::unique('users','telefono')->ignore($usuario)],
            'rol_id'       => 'required|exists:roles,id',
            'email'     => ['bail','required',Rule::unique('users','email')->ignore($usuario)],
            'direccion' => 'nullable',

        ],[
            'nombre.required'   => 'El nombre es importante',
            'nombre.string'     => 'El nombre no es valido',
            'apellido.required' => 'El apellido es importante',
            'apellido.string'   => 'El apellido no es valido',
            'apellido.min'      => 'El apellido debe tener al menos de dos caracteres',
            'nombre.min'        => 'El nombre debe tener al menos de dos caracteres',
            'telefono.required' => 'Este campo es obligatorio',
            'telefono.unique'   => 'El teléfono ya lo esta utilizando otro usuario, debe ser único',
            'rol_id.required'   => 'Este campo es obligatorio',
            'rol_id.exists'     => 'El rol no existe verifique',
            'email.required'    => 'Este campo es obligatorio',
            'email.email'       => 'El email no es valido por favor verifique',
            'email.unique'      => 'El email debe ser único ya otro usuario lo esta usando.',
        ]);

        $usuario->removeRole($usuario->rol);
        $usuario->nombre    = $datos['nombre'];
        $usuario->apellido  = $datos['apellido'];
        $usuario->telefono  = $datos['telefono'];
        $usuario->rol_id    = $datos['rol_id'];
        $usuario->email     = $datos['email'];
        $usuario->direccion = $datos['direccion'];
    
        try{
            DB::beginTransaction();
            $usuario->assignRole($datos['rol_id']);
            $usuario->save();

            DB::commit();

            $usuario->rol;
            $usuario->avatar = $usuario->getAvatar();
            if($usuario->roles->first()){
                $usuario->roles->first()->permissions;
            } 
            $chats = $usuario->chats;

            foreach ($chats as $key => $value) {
                $chats[$key]->mensajes = $value->mensajes;
                $chats[$key]->usuarios = $value->usuarios;
            }

            $usuario->chats = $chats;

            $result = true;
        }catch(Exception $e){
            DB::rollBack();
            $result = false;
        }
       
       return response()->json(['success' => $result,'usuario' => $usuario]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $usuario){

        try{
            DB::beginTransaction();

                $usuario->delete();

            DB::commit();
            $result = true;
        }catch(Excelption $e){
            DB::rollBack();
            $result = false;
        }
        
        return response()->json(['success' => $result]); }


    public function perfil(Request $request){

        return view('admin.usuario.perfil');}


    public function getUsuarios(){

        $usuarios = User::get();
        
        foreach ($usuarios as $key => $usuario) {
            $usuario->rol;
            
            if($usuario->roles->first()){
                $usuario->roles->first()->permissions;
            } 

            $usuario->avatar = $usuario->getAvatar();
            $chats = $usuario->chats;

            foreach ($chats as $key => $value) {
                $chats[$key]->mensajes = $value->mensajes;
                $chats[$key]->usuarios = $value->usuarios;
            }
            $usuario->chats = $chats;

        }

        return response()->json($usuarios);
    }

    public function listar(Request $request){
            
            $datos = User::get();

            return \DataTables::of($datos)
                                ->addIndexColumn()
                                ->editColumn('name',function(User $usuario){
                                     return $usuario->nombre. ' ' . $usuario->apellido;
                                })
                                ->addColumn('rol',function(User $usuario){
                                    return ($usuario->rol) ? $usuario->rol->name : 'Usuario';
                                })
                                ->addColumn('creado',function($row){

                                    return $row->created_at->format('d M \d\e Y H:i:s');
                                })
                                

                                ->addColumn('action',function($row){
                                    $btn = '<button type="button" class="btn btn-outline-primary" title="Editar Usuario" data-toggle="tooltip" data-action="editarUsuario"><i class="fas fa-edit"></i></button>';
                                    $btn .= '<button type="button" class="btn btn-outline-danger eliminar-usuario" data-action="eliminarUsuario" title="Eliminar usuario" data-toggle="tooltip" data-placement="left"><i class="fas fa-trash"></i></button>';


                                  
                                    return '<div class="btn-group btn-group-sm">'.$btn.'</div>';
                                })
                                ->rawColumns(['action'])
                                ->make(true);}

    public function EstablecerContrasena(Request $request,User $usuario){

        $datos = $request->validate([
            'contrasena' => 'required|min:6',
            'repetir-contrasena' => 'same:contrasena'
        ],[
            'contrasena.required'     => 'La contraseña es importante no la olvides.',
            'contrasena.min'          => 'La contraseña tiene que tener minimo 6 caracteres.',
            'repetir-contrasena.same' => 'Las contraseñas no son iguales verifica.'
        ]);

        $usuario->password = Hash::make($datos['contrasena']);
        $usuario->is_password = true;
        $usuario->save();

        return $this->autenticar($usuario, $request);}

    /**
     *Usado para autenticar manualmente al usuario
     * 
     * @param [App\user $usuario]
     * @param [Illuminate\Http\Request $request]
     */
    private function autenticar(User $usuario, Request $request){

        $credenciales = ['email' => $usuario->email, 'password' => $request->get('contrasena')];

        if(Auth::attempt($credenciales)){

            return redirect()->intended('home');

        }
    }

    public function updatePerfil(Request $request, User $usuario){



        $datos = $request->validate([
            'nombre'           => 'required|string|min:2',
            'apellido'         => 'required|string|min:2',
            'telefono'         => ['bail','required',Rule::unique('users','telefono')->ignore($usuario)],
            'fecha_nacimiento' => ['bail','required'],
            'email'            => ['bail','required',Rule::unique('users','email')->ignore($usuario)],
            'direccion'        => 'nullable',
            'rol_id'           => 'nullable',
        ],[
            'nombre.required'           => 'El nombre es importante',
            'nombre.string'             => 'El nombre no es valido',
            'apellido.required'         => 'El apellido es importante',
            'apellido.string'           => 'El apellido no es valido',
            'apellido.min'              => 'El apellido debe tener al menos de dos caracteres',
            'nombre.min'                => 'El nombre debe tener al menos de dos caracteres',
            'telefono.required'         => 'Este campo es obligatorio',
            'telefono.unique'           => 'El teléfono ya lo esta utilizando otro usuario, debe ser único',
            'fecha_nacimiento.required' => 'La fecha de Nacimiento es importante no debe faltar',
            'email.required'            => 'Este campo es obligatorio',
            'email.email'               => 'El email no es valido por favor verifique',
            'email.unique'              => 'El email debe ser único ya otro usuario lo esta usando.',
        ]);


        $usuario->nombre           = $datos['nombre'];
        $usuario->apellido         = $datos['apellido'];
        $usuario->telefono         = $datos['telefono'];
        $usuario->fecha_nacimiento = $datos['fecha_nacimiento'];
        $usuario->email            = $datos['email'];
        $usuario->direccion        = $datos['direccion'];

        $usuario->save();

        if(isset($datos['rol_id'])){
            $usuario->removeRole($usuario->rol);
            $usuario->assignRole($datos['rol_id']);
            $usuario->rol_id = $datos['rol_id'];
            $usuario->save();
        }   

        $usuario->rol;
        $usuario->avatar = $usuario->getAvatar();
        if($usuario->roles->first()){
            $usuario->roles->first()->permissions;
        } 
        $chats = $usuario->chats;

        foreach ($chats as $key => $value) {
            $chats[$key]->mensajes = $value->mensajes;
            $chats[$key]->usuarios = $value->usuarios;
        }

        $usuario->chats = $chats;


        return response()->json(['success' => true, 'usuario' => $usuario]);
    }

    public function uploadAvatar(Request $request){
        
        $usuario = Auth::user();

        $avatar = $request->file('filepond');

        Storage::disk('img-perfil')->delete($usuario->imagen);

        $avatarName = sha1(Carbon::now().$usuario->id).'.'.$avatar->getClientOriginalExtension();
        Storage::disk('img-perfil')->put($avatarName,File::get($avatar));
                
        $usuario->imagen = $avatarName;
        $usuario->save();

        return response()->json($usuario->getAvatar());
    }

    public function changePassword(Request $request){

        $v = Validator::make($request->all(),[
            'pass_actual' => ['required',function($attribute,$value,$fail){


                if(!Hash::check($value,Auth::user()->password)){
                    // dd(Hash::make($value). ' - ' . Auth::user()->password);
                    $fail('Su contraseña no coincide con la actual');
                }
            }],
            'pass_new'     => 'required|min:6',
            'pass_confirm' => 'required|same:pass_new'
        ],[
            'pass_actual.required'     => 'Su contraseña es requeridad para poder actualizarla',
            'pass_new.required' => 'Su nueva contraseña es obligatoria',
            'pass_new.min'      => 'Su contraseña debe ser mayor a 6 caracteres',
            'pass_confirm.same' => 'La contraseñas no son iguales'
        ])->validate();

        $usuario = Auth::user();

        $usuario->password = Hash::make($v['pass_new']);


        $usuario->save();

       
        
        return response()->json(['success' => true]);
    }

    public function perfilDatos(){

        $usuario = Auth::user();

        $datos = [
                'nombre'           => $usuario->nombre,
                'apellido'         => $usuario->apellido,
                'telefono'         => $usuario->telefono,
                'direccion'        => $usuario->direccion,
                'email'            => $usuario->email,
                'rol'              => $usuario->roles,
                'avatar'           => asset('storage/img-perfil/'.$usuario->imagen),
                'fecha_nacimiento' => $usuario->fecha_nacimiento,
                'id'               => $usuario->id,
        ];
        return response()->json($datos);
    }

    public function refresh(){
        $data = [
            'avatar' => Auth::user()->getAvatar(),
            'usuario' => Auth::user(),

        ];

        return response()->json($data);
    }

}
