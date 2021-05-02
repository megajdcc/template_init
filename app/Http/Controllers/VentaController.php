<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\{DB,Auth,Storage,Mail};
use Madzipper;
use App\Notifications\enviarEmail;
use App\Mail\CompraExitosa;


class VentaController extends Controller
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
            'monto'           => 'required',
            'fotos'           => 'required',
            'pagado'          => 'required',
            'respon_paypal'   => 'required',
            'condicion_envio' => 'required',
            'email_envio'     => 'nullable',
            'comprador_id'    => 'required'
        ]);


        try{
            DB::beginTransaction();

                $venta = Venta::create([
                        'monto'           => $data['monto'],
                        'fotos'           => json_encode($data['fotos']),
                        'pagado'          => $data['pagado'],
                        'respon_paypal'   => json_encode($data['respon_paypal']),
                        'condicion_envio' => $data['condicion_envio'],
                        'email_envio'     => $data['email_envio'],
                        'comprador_id'    => $data['comprador_id'],
                ]);

               $url_zip = $this->comprimirArchivos($venta);
                $venta->url_zip = $url_zip;
                $venta->save();

                $venta->comprador;

                $venta->comprador->notify(new enviarEmail($venta));
                
                if($venta->condicion_envio == 2){
                    $mensaje = 'Gracias por tu compra, puedes revisar el modulo de compras cuando lo desees para mas detalles.';
                }else{
                    
                    $mensaje = '¡Gracias por tu compra!. Para mas detalle, puedes revisar el modulo de compras cuando lo desees, te hemos enviado al correo:'.$venta->email_envio.', un archivo zip, con tus fotos, si no lo ves en bandeja de entrada puedes revisar la bandeja de Spam.';

                    Mail::to($venta->email_envio)->cc($venta->comprador)->send(new CompraExitosa($venta));
                }

            DB::commit();
            $result = true;

            $carrito = $this->limpiarCarritoCompra($venta->comprador);

        }catch(Exception $e){
            DB::rollBack();
            $result = false;
        }

        return response()->json(['success' => $result, 'venta' => ($result) ? $venta : null,'mensaje' => $mensaje,'carrito' => ($result) ? $carrito : null]);

    }

    private function limpiarCarritoCompra(User $usuario){
        
        $usuario->fotoInCarrito()->detach();
        return $usuario->fotoInCarrito;
    
    }


    private function comprimirArchivos(Venta $venta){

        $fotos = json_decode($venta->fotos);
        Storage::disk('ventas')->makeDirectory('venta'.$venta->id);
        foreach ($fotos as $key => $foto) {
            $photo = explode('/',$foto->nombre);
            $photo = $photo[count($photo) -1];
            $result_copy = Storage::copy('public/imagenes/originales/'.$photo,'public/ventas/venta'.$venta->id.'/'.$photo);
        }

        $files =  glob(storage_path('app/public/ventas/venta'.$venta->id.'/*'));

        Madzipper::make(storage_path('app/public/zips/venta'.$venta->id.'.zip'))->add($files)->close();
        $url_zip = asset('storage/zips').'/venta'.$venta->id.'.zip';
        return $url_zip;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function show(Venta $venta)
    {
        return view('home');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function edit(Venta $venta)
    {
        return view('home');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Venta $venta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Venta $venta)
    {
        //
    }


    public function cargar(User $usuario){
        $ventas = $usuario->compras;

        foreach ($ventas as $key => $venta) {
            $venta->comprador;
        }

        return response()->json($ventas);
    }



    public function listarMisCompras(User $usuario){

        $compras = $usuario->compras;


        return \DataTables::of($compras)
                            ->addIndexColumn()
                            ->addColumn('monto',function($row){
                                return '$ '.number_format((float)$row->monto,2,'.',',').' USD';
                            })

                            ->addColumn('fotos',function($row){

                                $fotos = json_decode($row->fotos);

                                $cantidad = (count($fotos) > 1) ? count($fotos).' Fotos' : count($fotos).' Foto';

                                return $cantidad;
                            })
                            ->addColumn('fecha',function($row){
                                return date('d M \d\e Y H:i:s',strtotime($row->created_at));
                            })
                            ->addColumn('action' ,function($row){
                                $btn ='';

                                $btn = '<button type="button" class="btn btn-outline-primary" data-action="reenviar" title="Reenviarme Fotos por correo">Reenviarme fotos</button>';

                                return '<div class="btn-group btn-group-sm">'.$btn.'</div>';
                            })
                            ->rawColumns(['action'])
                            ->make(true);


    }


    public function reenviarEmail(Venta $compra){
        Mail::to($compra->comprador)->send(new CompraExitosa($compra));
        return response()->json(['success' => true]);
    
    }



}
