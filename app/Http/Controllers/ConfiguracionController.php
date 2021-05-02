<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;
use Carbon\Carbon;

use Illuminate\Support\Facades\{DB, Auth,File,Storage};


class ConfiguracionController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Configuracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function show(Configuracion $configuracion)
    {
        return view('home');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Configuracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function edit(Configuracion $configuracion)
    {
        return view('home');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Configuracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Configuracion $configuracion)
    {
        
        $data = $request->validate([
            'paypal_id'         => 'nullable',
            'paypal_secret'     => 'nullable',
            'production_paypal' => 'nullable',
            ]);

        try{    
            DB::beginTransaction();

                $configuracion->paypal_id = $data['paypal_id'];
                $configuracion->paypal_secret  = $data['paypal_secret'];
                $configuracion->production_paypal = $data['production_paypal'];

                $configuracion->save();
    
            DB::commit();
            $result = true;
        }catch(Exception $e){
            DB::rollBack();
        }

        return response()->json(['success' => $configuracion, 'configuracion' => $configuracion]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Configuracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Configuracion $configuracion)
    {
        //
    }

    public function cargar(){
        return response()->json(Configuracion::first());
    }


    public function uploadMarcaAgua(Request $request, Configuracion $configuracion){
        
        $foto = $request->file('foto');
        
          try{
            DB::beginTransaction();
                
                $fotoName = sha1(Carbon::now().$configuracion->id).'.'.$foto->getClientOriginalExtension();
                Storage::disk('marca_agua')->put($fotoName,File::get($foto));

                $fotoName = asset('storage/marca_agua').'/'.$fotoName;

                $configuracion->marca_agua = $fotoName;
                $configuracion->save();
            
            DB::commit();
            $result = true;
          
          }catch(Exception $e){
            DB::rollBack();
            $reult = false;
          }

          return response()->json(['foto' => $fotoName, 'configuracion' => $configuracion]);

    }


}
