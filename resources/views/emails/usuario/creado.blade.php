@component('mail::message')
# Hola **{{ $Nombre }}**.  
> Ha recibido una invitacion a formar parte del **Sistema InverAgro**, y queremos darte ¡la mas cordial bienvenida!  

> Te ha sido asignado un usuario con el rol de **{{ $Rol }}**.  

> Para ingresar al sistema solo debes crear una contraseña, y validar tu email registrado **{{ $Email }}** en el que recibirás un correo explicativo con instrucciones.  

## ¡Felicidades! ##  

@component('mail::button', ['url' => $Url, 'color' => 'success'])
	Establecer Contrase&ntilde;a
@endcomponent  

> Para la aclaracion de cualquier duda, cuentas con asistencia y soporte con el equipo de InverAgro.  
<a href="mailto:megajdcc2009@gmail.com ?subject=Soporte Inver Agro">Soporte Inver Agro</a>  
Gracias por tu preferencia.  

El equipo de InverAgro.  
&copy; {{ date('Y') }} InverAgro. Todos los derechos Reservados.
@endcomponent
