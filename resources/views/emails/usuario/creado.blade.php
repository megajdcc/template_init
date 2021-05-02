@component('mail::message')

# Welcome **{{ $Nombre }}**.  
> You've received authorization to enter the **Fernando Aguilar** Photo System and view your exclusive photographs.

> Your access credentials are: 

>>User: **{{ $Email }}**

@component('mail::button', ['url' => $Url, 'color' => 'success'])
	Please set your password below.
@endcomponent  

## See you inside! ##  


# Hola **{{ $Nombre }}**.  
> Has recibido autorización para ingresar al sistema de fotos de **Fernando Aguilar**, y ver sus fotografías exclusivas  

>Tus credenciales son:

>>Usuario:**{{ $Email }}**  

@component('mail::button', ['url' => $Url, 'color' => 'success'])
	Por favor establezca su contraseña a continuación
@endcomponent  

&copy; {{ date('Y') }} Fernando Aguilar. All rights reserved.
@endcomponent
