<?php

namespace App\Http\Controllers;

use App\Models\{Album,Foto};
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\{DB, Auth,File,Storage};

use Illuminate\Validation\Rule;

use Image;

use App\Models\Configuracion;



class AlbumController extends Controller
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
        $data = $request->validate([
            'nombre' => 'required|unique:albums,nombre'
        ],[
            'nombre.required' => 'El nombre es importante no lo olvides',
            'nombre.unique' => 'El nombre del álbum ya esta siendo usado intenta con otro'
        ]);

        try{
            DB::beginTransaction();

                $album = Album::create([
                    'nombre' => $data['nombre'],
                    'usuario_id' => Auth::id(),
                ]);

                $album->publicante;
                $album->fotos;


            DB::commit();
            $result = true;
        }catch(Exception $e){
            DB::rollBack();
            $result = false;
        }


        return response()->json(['success' => $result, 'album' => ($result) ? $album : null]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function show(Album $album)
    {
        return view('home');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function edit(Album $album)
    {
        return view('home');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Album $album)
    {
        
        $data = $request->validate([
            'nombre' => ['required',Rule::unique('albums','nombre')->ignore($album)]
        ],[
            'nombre.required' => 'El nombre es importante no lo olvides',
            'nombre.unique' => 'El nombre del álbum ya esta siendo usado intenta con otro'
        ]);

        try{
            DB::beginTransaction();

                $album->nombre = $data['nombre'];
                $album->save();

                $album->publicante;
                $album->fotos;


            DB::commit();
            $result = true;
        }catch(Exception $e){
            DB::rollBack();
            $result = false;
        }

        return response()->json(['success' => $result, 'album' => ($result) ? $album : null]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function destroy(Album $album)
    {
        try{
            DB::beginTransaction();

            foreach ($album->fotos as $key => $foto) {
                Storage::disk('fotos_originales')->delete($foto->nombre);
                Storage::disk('fotos_con_marcas')->delete($foto->foto_con_marca);
            }

            $album->delete();

            DB::commit();

            $result = true;

        }catch(Exception $e){
            DB::rollBack();

            $result = false;
            $album->publicante;
            $album->fotos;

        }

        return response()->json(['success' => $result, 'album' => (!$result) ? $album : null]);

    }


    public function cargar(){

        if(Auth::user()->rol->name == 'User'){
            $albumes = Auth::user()->AlbumesAsignados();
        }else{
            $albumes = Album::get();
        }

        foreach ($albumes as $key => $album) {
                $albumes[$key]->publicante;
                $albumes[$key]->fotos;

        }

        return response()->json($albumes);
    }


    public function uploadFoto(Request $request, Album $album){
          
          $foto = $request->file('foto');

          try{
            DB::beginTransaction();
                
                $fotoName = sha1(Carbon::now().$album->id).'.'.$foto->getClientOriginalExtension();
                
                Storage::disk('fotos_originales')->put($fotoName,File::get($foto));

                $fotoConMarca = Image::make($foto);
                


                $marca_agua = Configuracion::first()->marca_agua;
                $marca_agua = explode('/',$marca_agua);
                $marca_agua = $marca_agua[count($marca_agua) - 1];

                $marca = Image::make(asset('storage/marca_agua').'/'.$marca_agua);



                $marca->resize($fotoConMarca->width(),$fotoConMarca->height());

                // $fotoConMarca->insert(asset('storage/marca_agua').'/'.$marca_agua,'top-left');
                $fotoConMarca->insert($marca,'top-left');

                // $fotoConMarca->fill(asset('storage/marca_agua').'/'.$marca_agua,5,5);


                // dd($fotoName);

                $fotoConMarca->save(public_path('storage/imagenes/conmarcas/').$fotoName,100);

                $foto_original = asset('storage/imagenes/originales').'/'.$fotoName;
                $foto_con_marca  = asset('storage/imagenes/conmarcas').'/'.$fotoName;
              
                $imagen = Foto::create([
                    'nombre'         => $foto_original,
                    'foto_con_marca' => $foto_con_marca,
                    'album_id'       => $album->id,
                    'precio'         => $request->get('precio')
                ]);


            
            DB::commit();
            $result = true;
            
            $album->publicante;
            $album->fotos;

          }catch(Exception $e){
            DB::rollBack();
            $reult = false;
          }

          return response()->json(['imagen' => $imagen,'album' => $album]);
    }


    public function renombrar(Request $request, Album $album){

        $data = $request->validate([
            'nombre' => ['required', Rule::unique('albums','nombre')->ignore($album)]
        ],[
            'nombre.required' => "El nombre del álbum es importante no lo olvides",
            'nombre.unique' => 'El nombre ya esta siendo usado, intenta con otro',
        ]);


        try{
            DB::beginTransaction();

            $album->nombre = $data['nombre'];
            $album->save();

            $album->publicante;
            $album->fotos;

            DB::commit();
            $result = true;

        }catch(Exception $e){
            DB::rollBack();
            $result = false;
        }

        return response()->json(['success' => $result, 'album' => $album]);
    }


    public function eliminarFoto(Album $album, Foto $foto){

        try{

            DB::beginTransaction();

            Storage::disk('fotos_originales')->delete($foto->nombre);
            Storage::disk('fotos_con_marcas')->delete($foto->foto_con_marca);
            $foto->delete();

            DB::commit();
            $result = true;
        }catch(Exception $e){
            DB::rollBack();

            $result = false;

            $foto->album;

        }

        return response()->json(['success' => $result, 'foto' => (!$result) ? $foto : null]);


    }


    public function cambiarPrecioFoto(Request $request, Foto $foto){

        $data = $request->validate([
            'precio' => 'required',
        ],[
            'precio.required' => 'El precio es importante',
        ]);


        try{

            DB::beginTransaction();
                $foto->precio = $data['precio'];
                $foto->save();
                


            DB::commit();
            $result = true;
             $foto->album;
        }catch(Exception $e){
            DB::rollBack();
            $result = true;
        }

        return response()->json(['success' => $result, 'foto' => $foto]);
    }

}
