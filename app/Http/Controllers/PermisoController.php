<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;
use App\Models\{Permiso};
use Illuminate\Support\Facades\DB;

class PermisoController extends Controller
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

         return view('admin.permisos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'name' => 'required|unique:permissions,name',
        ],[
            'name.required' => 'Este campo es necesario',
            'name.unique' => 'Este Permiso ya está creado, no puede crear uno igual',
        ]);


        try{

            DB::beginTransaction();
                $permiso  = Permiso::create([
                'name' => $datos['name'],
                ]);
                
            DB::commit();
            $result = true;
            $message ='Se ha creado con exito el permiso';

        }catch(Exception $e){
              DB::rollBack();
              $message = 'No se ha podido registrar el permiso';
            $result = true;
        }

        return response()->json(['success' => $result,'permiso' => ($result) ? $permiso : null, 'message' => $message]);
   
    }



     public function getPermisos(){
        $data = [
            'permisos' => Permission::get(),
        ];


        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permiso)
    {

        return view('admin.permisos.edit',compact('permiso'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permiso $permiso)
    {
        $datos = $request->validate([
            'name' => ['required',Rule::unique('permissions','name')->ignore($permiso)],
        ],[
            'name.required' => 'Este campo es necesario',
            'name.unique' => 'Este permiso ya está creado, no puede crear uno igual',
        ]);

        try{
                DB::beginTransaction();
                $permiso->name = $datos['name'];
                $permiso->save();
                    
                DB::commit();
                $message = 'Se ha actualizado con exito el permiso';
                $result = true;
        }catch(Exception $e){
                DB::rollBack();
                $message = 'No se pudo actualizar el permiso';

                $result = false;
        }

        return response()->json(['success' => $result, 'permiso' => ($result) ? $permiso : null, 'message' => $message]);
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Permission $permiso)
    {

        try {
            DB::beginTransaction();
                $permiso->delete();
            DB::commit();
            $result = true;
        } catch (Exception $e) {
            DB::rollBack();
            $result = false;
        }

        return response()->json(['success' => $result]);
    
    }



    public function listarPermisos(Request $request){

            $datos = Permission::get();

               return \DataTables::of($datos)
                    ->addIndexColumn()
                    ->addColumn('action',function($row){

                        $btn = '<button type="button" class="btn btn-outline-primary" title="Editar permiso" data-action="editarPermiso" data-toggle="tooltip"><i class="fas fa-edit mx-2"></i></button>';

                        $btn .= '<button type="button" class="btn btn-outline-danger eliminar-permiso" data-action="eliminarPermiso" title="Eliminar Permiso"><i class="fa fa-trash mx-2"></i></button>';

                        return '<div class="btn-group btn-group-sm">'.$btn.'</div>';

                    })
                    ->rawColumns(['action'])
                    ->make(true);
    }


    public function getPermissions(){
        $permisos = Permiso::get();

        return response()->json($permisos);

    }
}
