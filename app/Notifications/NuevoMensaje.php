<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Mensaje;

class NuevoMensaje extends Notification implements ShouldQueue
{
    use Queueable;


    protected $mensaje;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Mensaje $mensaje)
    {
        $this->mensaje = $mensaje;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {   

        return [
               'mail'
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Nuevo mensaje de '. $this->mensaje->usuario->getNombreCompleto())
                    ->greeting('Hola '.$notifiable->getNombreCompleto().'!')
                    
                    ->line('Tienes un nuevo mensaje de: '.$this->mensaje->usuario->getNombreCompleto())
                    ->line('Mensaje:'. $this->mensaje->mensaje)
                    ->line('No esperes mucho, puedes responderle desde tu panel')
                    ->action('Entrar a mi panel', url(env('APP_URL')))
                    ->line('Gracias por seguir usando nuestro Aplicativo!.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
