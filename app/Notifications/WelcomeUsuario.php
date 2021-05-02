<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

use App\Mail\UsuarioCreado;

class WelcomeUsuario extends Notification implements ShouldQueue
{
    
    use Queueable;

    protected $usuario;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->usuario = $user;

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
            'mail',
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
        return (new UsuarioCreado($this->usuario))
            ->to($this->usuario->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable){
        return [
            'titulo' => 'Bienvenido a '.env('APP_NAME'),
            'mensaje' => 'Hola '.$this->usuario->nombre. ' '.$this->usuario->Apellido.'! Bienvenido a '.env('APP_NAME'),
               'btn'     => false,
               'url' => '',
             ];
    }

}
