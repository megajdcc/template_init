@component('mail::message')
# Gracias por tu compra en {{ env('APP_NAME') }}

> Las Fotos te los adjuntamos en un archivo Zip.

> Puedes ver tus compras paras mas detalles en la cuenta del afiliado en el siguiente enlace:

@component('mail::button', ['url' => $url, 'color' => 'success'])
	Ver Compras
@endcomponent 

## !Gracias por seguir usando nuestra aplicación!.
@endcomponent
