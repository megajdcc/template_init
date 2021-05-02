<?php

namespace App\Http\Controllers;

use App\Models\{Observacion, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Hash, Storage, File, Auth,Notification};
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Notifications\nuevaObservacion;


class ObservacionController extends Controller
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
        
        $datos = $request->validate([
            'modulo'     => 'required',
            'comentario' => 'required',
            'usuario_id' => 'required',
            'adjunto'    => 'nullable',
        ]);


        try{
            DB::beginTransaction();

            
            $adjunto = $request->file('adjunto');

            if($adjunto){
                $adjuntoName = sha1(Carbon::now()).'.'.$adjunto->getClientOriginalExtension();
                Storage::disk('observaciones_adjuntos')->put($adjuntoName,File::get($adjunto));
            }
            
                $observacion = Observacion::create([
                    'modulo'     => $datos['modulo'],
                    'comentario' => $datos['comentario'],
                    'adjunto'    => ($adjunto) ? $adjuntoName : null,
                    'usuario_id' => $datos['usuario_id']
                ]);


                $administradores = User::whereHas('rol',function(Builder $query){
                    return $query->where('name','Super Administrador');
                })->get();


                Notification::send($administradores,new nuevaObservacion($observacion));

                // broadcast(new ObservacionModulo($observacion));


            DB::commit();
            $result = true;
        }catch(Exception $e){
            DB::rollBack();
            $result = false;
        }


        return response()->json(['success' => $result]);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Observacion  $observacion
     * @return \Illuminate\Http\Response
     */
    public function show(Observacion $observacion)
    {
        return view('home');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Observacion  $observacion
     * @return \Illuminate\Http\Response
     */
    public function edit(Observacion $observacion)
    {
        return view('home');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Observacion  $observacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Observacion $observacione)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Observacion  $observacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Observacion $observacione)
    {
        try {
            DB::beginTransaction();

                $observacione->delete();

            DB::commit();
            $result = true;

        } catch (Exception $e) {
            DB::rollBack();

            $result = false;
        }

        return response()->json(['success' => $result]);
    }


    public function cargarObservaciones(){

        $observaciones = Observacion::get();


        foreach ($observaciones as $key => $observacion) {
            $observacion->usuario;
        }

        return response()->json($observaciones);
    
    }

    public function atendido(Observacion $observacion){

        try{
            DB::beginTransaction();

                $observacion->atendido = true;
                $observacion->save();
            
            DB::commit();
            $result = true;
        }catch(Exception $e){
            DB::rollBack();
            $result = false;
        }

        return response()->json(['success' => $result]);
    }



    public function listar(){


        return \DataTables::of(Observacion::get())
                            ->addIndexColumn()
                            ->editColumn('atendido',function($row){
                                return ($row->atendido) ? 'Si' : 'Sin atender aun';
                            })
                            ->addColumn('usuario',function($row){
                                return $row->usuario->getNombreCompleto();
                            })

                            ->addColumn('fecha',function($row){
                                return date('d-m-Y H:i:s A',strtotime($row->created_at));
                            })

                            ->addColumn('action',function($row){
                                $btn = '';
                                if(!$row->atendido){
                                    $btn .= '<button type="button" class="btn btn-outline-success" title="Marcar como atendido" data-action="atender"><i class="fas fa-toggle-off"></i></button>';
                                }
                                if($row->adjunto){

                                    $btn .= '<a href="'.asset('/storage/observaciones/adjuntos').'/'.$row->adjunto.'" title="Adjunto" target="_blank" class="btn btn-outline-info"><i class="fas fa-newspaper"></i></a>';
                                }

                                $btn .= '<button type="button" class="btn btn-outline-dark" data-action="mostrar" title="Ver Observación"><i class="fas fa-eye"></i></button>';

                                $btn .= '<button type="button" class="btn btn-outline-danger" data-action="eliminar" title="Eliminar Observación"><i class="fas fa-trash"></i></button>';

                                return '<div class="">'.$btn.'</div>';

                            })
                            ->rawColumns(['action'])
                            ->make(true);


    }




}
