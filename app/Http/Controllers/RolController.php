<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\Rol;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\{Auth,DB};

class RolController extends Controller
{

    /**
     * [$request description]
     * @var [Request $request]
     */
    protected $request; 
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return view('home');
    }


    public function getRoles(){

        $roles = Rol::get();

        return response()->json($roles);
    }


    public function roles(){
        $roles = Rol::get();

        foreach ($roles as $key => $value) {
            $permisos = collect([]);

            foreach ($value->permisos as $k => $v) {
                $permisos->push($v->id);
            }

            $roles[$key]->permissions = $permisos;
        }   

        return response()->json($roles);
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permisos = Permission::get();
        
        return view('admin.roles.create',compact('permisos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->request = $request;

        $datos = $request->validate([
            'name' => ['bail','required',Rule::unique('roles','name')],
            'permisos' => 'nullable',
            'permisos.*' => 'required',
        ],[
            'name.required' => 'Este campo es necesario',
            'name.unique' => 'Este rol ya está creado, no puede crear uno igual',

        ]);


        try{
            DB::beginTransaction();
                $role  = Rol::create([
                    'name' => $datos['name'],
                ]); 

                if(isset($datos['permisos'])){
                     foreach ($datos['permisos'] as $key => $permiso) {
                        $role->givePermissionTo($permiso);
                    }
                }

                $permisos = collect([]);

                foreach ($role->permisos as $k => $v) {
                    $permisos->push($v->id);
                 }
                 $role->permis = $permisos;
                 
            DB::commit();
            $result = true;

            $message ="El rol se ha creado con exito";
        }catch(Exception $e){
            DB::rollBack();
            $result = false;
            $message = 'EL rol no se pudo crear';
        }

        return response()->json(['success' => $result,'rol' => ($result) ? $role : null, 'message' => $message]);

    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function show(Rol $rol)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Spatie\Model\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {

        $permisos = Permission::where('guard_name', Auth::user()->getGuardName())->get();   

        return view('admin.roles.edit',compact('role','permisos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Spatie\Model\Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rol $role)
    {
        $datos = $request->validate([
            'name' => ['required',Rule::unique('roles','name')->ignore($role)],
            'permisos.*' => 'required',
        ],[
            'name.required' => 'Este campo es necesario',
            'name.unique' => 'Este rol ya está creado, no puede crear uno igual',
        ]);

         try{
            DB::beginTransaction();
                $role->name = $datos['name'];

                $role->save();

                if(isset($datos['permisos'])){
                    foreach($role->permissions as $key => $permiso) {
                        $role->revokePermissionTo($permiso);
                    }

                    foreach($datos['permisos'] as $key => $permiso){
                         $role->givePermissionTo($permiso);
                    }
                }
                
                $permisos = collect([]);

                foreach ($role->permisos as $k => $v) {
                    $permisos->push($v->id);
                 }

                 $role->permis = $permisos;

            DB::commit();
            $result = true;

            $message ="El rol se ha actualizado con exito";
        }catch(Exception $e){
            DB::rollBack();
            $result = false;
            $message = 'EL rol no se pudo actualizar';
        }

         return response()->json(['success' => $result,'rol' => ($result) ? $role : null, 'message' => $message]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Role $role)
    {
    
        
        try{
            DB::beginTransaction();
              $role->delete();

            DB::commit();
            $result = true;

        }catch(Exception $e){
            DB::rollBack();
            $result = true;
        }
              
        return response()->json(['success' => $result]);
    }



    public function listar(Request $request){

            // $datos = Rol::where('name','!=','Desarrollador')->get();
            $datos = Rol::where('guard_name' , 'web')->get();
            


            foreach ($datos as $key => $value) {
                
                $permisos = collect([]);

                foreach ($value->permissions as $k => $v) {
                    $permisos->push($v->id);
                }

                $datos[$key]->permisos = $permisos;

            }


            return \DataTables::of($datos)
                            ->addIndexColumn()
                            ->addColumn('actions',function($row){

                                $btn = '<button type="button" class="btn btn-outline-primary" data-action="editarRol" title="Editar Rol"><i class="fa fa-edit mx-2"></i></button>';

                                $btn .= '<button type="button" class="btn btn-outline-danger eliminar-rol" data-action="eliminarRol" title="Eliminar Rol"><i class="fa fa-trash mx-2"></i></button>';

                                return '<div class="btn-group btn-group-sm">'.$btn.'</div>';

                            })
                            ->rawColumns(['actions','permisos'])
                            ->make(true);
        
    }



    public function listarPermisosRole(Request $request, Role $role){
         
         if($request->ajax()){

            $datos = $role->permissions;

            return \DataTables::of($datos)
                            ->addIndexColumn()
                            ->addColumn('action',function($row){
                                return '<button type="button" class="btn btn-danger revocar-permiso btn-sm" data-id="'.$row->id.'"><i class="fa fa-trash mx-2"></i></button>';
                            })
                            ->rawColumns(['action'])
                            ->make(true);
         }

    }


    public function revocarPermiso(Request $request,Permission $permiso,Role $role){

         if($request->ajax()){
                
                $role->revokePermissionTo($permiso);

                return json_encode(['result' => true]);
         
         }

    }
}
