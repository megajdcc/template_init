<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Venta;

class CompraExitosa extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $venta;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Venta $venta)
    {
        $this->venta = $venta;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $zip = explode('/',$this->venta->url_zip);
        $zip = $zip[count($zip)-1];

        return $this->markdown('emails.enviarcorreo')
                        ->subject('Archivo con tus fotos Compradas')
                        ->with([
                        'url'    => env('APP_URL').'/compras'
                        ])
                        ->attachFromStorageDisk('archivos_zip',$zip,'file.zip');
    }
}
