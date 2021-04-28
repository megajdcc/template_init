<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        'App\Events\UsuarioConectado' => [
            'App\Listeners\InformarUsuarioConectado',
        ],
        
        'App\Events\ChatMensaje' => [
            'App\Listeners\MensajeUsuario',
        ],
        
        'App\Events\UsuarioDesconectado' => [
            'App\Listeners\InformarUsuarioDesconectado',
        ],


    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
