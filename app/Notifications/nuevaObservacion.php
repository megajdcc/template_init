<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Observacion;

class nuevaObservacion extends Notification implements ShouldQueue
{
    use Queueable;


    protected $observacion;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Observacion $observacion)
    {
        $this->observacion = $observacion;
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
        return (new MailMessage)
                    ->subject('Nueva Observación en el sistema')
                    ->greeting('¡Hola '.$notifiable->getNombreCompleto().'!')
                    ->line('El usuario: '.$this->observacion->usuario->getNombreCompleto().', ha generado una nueva observación.')
                    ->line('La observación dice lo siguiente: '.$this->observacion->comentario.'.Y realizo el comentario mientras estaba en el modulo: '.url(env('APP_URL').$this->observacion->modulo).'')
                    ->action('Ver en el sistema', url(env('APP_URL')))
                    ->salutation('Gracias por seguir usando nuestra aplicación!');
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
            'titulo' => 'Nueva observación en el sistema',
            'mensaje' => ['El usuario: '.$this->observacion->usuario->getNombreCompleto().', ha generado una nueva observación.','La observación dice lo siguiente: '.$this->observacion->comentario.'.Y realizo el comentario mientras estaba en el modulo: '.url(env('APP_URL').$this->observacion->modulo)],
            'btn' => true,
            'btnTitle' => 'Ver Observación',
            'url' => ['name' => 'observaciones']
        ];
    }
}
