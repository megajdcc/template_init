<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{ObservacionController, HomeController,ChatController, PermisoController, RolController, UserController};
use App\Models\User;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::put('usuario/{usuario}/establecer/contrasena',[UserController::class,'EstablecerContrasena'])->name('establecercontrasena');

Route::get('/usuario/{usuario}/establecer/contrasena',function(User $usuario){

			if($usuario->is_password){
				return redirect()->route('login');
			}else{
				return view('admin.usuario.establecercontrasena',['usuario' => $usuario]);
			}
});


Route::middleware('auth')->group(function(){

	Route::redirect('/','/home');

	Route::get('/home', [HomeController::class, 'index'])->name('home');
	Route::get('app/get/data',[HomeController::class,'getDataApp']);


	Route::middleware(['permission:Gestionar observaciones de modulos'])->group(function(){

			// Observaciones 
			//  Agregar El middleware de permiso de esta ruta
			Route::get('observaciones/listar',[ObservacionController::class,'listar']);
			Route::resource('/observaciones',ObservacionController::class);
			Route::get('/cargar/observaciones',[ObservacionController::class,'cargarObservaciones']);
			Route::get('/observaciones/{observacion}/marcar/atendido', [ObservacionController::class,'atendido']);
			Route::get('observaciones/{observacion}/marcar/atendido',[ObservacionController::class,'atendido']);
	});

// Companeros Chat

Route::get('/chat/get/companeros',[HomeController::class,'getCompaneros']);
Route::post('/chat/create/{usuario}',[ChatController::class, 'store']);
Route::put('chat/{chat}/send/mensaje',[ChatController::class,'sendMensaje']);


/*****************************/
/* NOTIFICACIONES
/*****************************/

	Route::get('notificaciones/{usuario}',function(User $usuario){
			$data= ['notificaciones' => $usuario->notifications, 'sinleer' => $usuario->unreadNotifications,'leidas' => $usuario->readNotifications]; 
			return response()->json($data);
	});

	Route::get('notificaciones/markread/{notificacion}/usuario/{usuario}', function($notificacion,User $usuario){
				
				try{
					DB::beginTransaction();
						$notificacion  = $usuario->unreadNotifications->where('id',$notificacion)->first();
						$notificacion->markAsRead();
					DB::commit();
					$result = true;
				}catch(Exception $e){
					DB::rollBack();
					$result =false;
				}
				
			return response()->json(['success' => $result, 'notificacion' => $notificacion ]);
	});




	Route::middleware(['permission:Gestionar roles y permisos'])->group(function(){
			// Permisos
				Route::get('cargar/permisos',[PermisoController::class,'getPermissions']);



				// Configuracion
				// 
				Route::prefix('configuracion')->group(function(){

					// Permisos
					Route::resource('permisos',PermisoController::class);
					Route::get('listar/permisos',[PermisoController::class,'listarPermisos'])->name('listar_permisos');
					Route::post('/revocar/permiso/{permiso}/role/{role}',[RolController::class,'revocarPermiso']);
					Route::post('/listar/permisos/role/{role}', [RolController::class,'listarPermisosRole']);


					// Roles
					Route::resource('roles',RolController::class);
					Route::get('roles/get/permisos',[PermisoController::class,'getPermisos'])->name('getPermisos');
					Route::get('roles/listar/table',[RolController::class,'listar']);
					Route::delete('roles/delete/{role}',[RolController::class,'destroy']);
					
				
				});

				Route::get('listar/roles',[RolController::class,'roles']);
				
	});



	Route::middleware(['permission:Gestionar usuarios'])->group(function(){
		// Usuario
		Route::get('/usuarios/all', [UserController::class,'getUsuarios']);

		Route::resource('usuarios', UserController::class)->middleware('format_telefono');
		Route::get('listar/usuarios',[UserController::class,'listar'])->name('listar_usuarios');
	});



	Route::view('/perfil','home');
	Route::post('upload/avatar',[UserController::class,'uploadAvatar'])->name('upload_avatar');
	Route::put('perfil/update/usuario/{usuario}',[UserController::class,'updatePerfil'])->name('update_perfil')->middleware('format_telefono');
	Route::post('cambiar/contrasena',[UserController::class,'changePassword'])->name('cambiar_contrasena_admin');

});