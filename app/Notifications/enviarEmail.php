<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\Venta;

class enviarEmail extends Notification implements ShouldQueue
{

    use Queueable;

    protected $venta,$cantidad_foto;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Venta $venta)
    {
        $this->venta = $venta;
        $fotos = json_decode($this->venta->fotos);
        $this->cantidad_foto = count($fotos);

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        if($this->cantidad_foto > 1){
            $cantidad = $this->cantidad_foto .' fotos';
        }else{
             $cantidad = $this->cantidad_foto .' foto';
        }

        return (new MailMessage)
                    ->subject('Confirmación de Compra')
                    ->greeting('¡Hola '.$notifiable->getNombreCompleto().'!')
                    ->line('Has tenido una compra exitosa de:'.$cantidad.'. Por un monto total de: $'. number_format((float) $this->venta->monto, 2 , '.',',').' USD')
                    ->line('Puedes revisar tus compras cuando quieras para mas detalles')
                    ->action('Ver mis compras', url('/compras'))
                    ->salutation('¡Gracias por seguir usando nuestra aplicación!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
         if($this->cantidad_foto > 1){
            $cantidad = $this->cantidad_foto .' fotos';
        }else{
             $cantidad = $this->cantidad_foto .' foto';
        }

        return [
            'titulo' => 'Gracias por tu compra',
            'mensaje' => ['Has tenido una compra exitosa de: '.$cantidad,'Por un monto total de: $ '.number_format((float) $this->venta->monto, 2 , '.',',').' USD','Puedes revisar tus compras cuando quieras para mas detalles','¡Gracias por seguir usando nuestra aplicación!'],
            'btn' => 'title',
            'btnTitle' => 'Ver mis compras',
            'url' => ['name' => 'compras']

        ];
    }
}
