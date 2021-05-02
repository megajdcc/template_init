@component('mail::layout')
	{{-- Header --}}
	@slot('header')
		@component('mail::header', ['url' => config('app.url')])
		{{-- {{ config('app.name') }} --}}
		<img src="{{ asset('/storage/logotipo.png') }}" class="logotipo_mail" alt="{{ env('APP_NAME') }}" style="width:auto; height:80px">
		@endcomponent
	@endslot

	{{-- Body --}}
	{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
© {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
@endcomponent
@endslot
@endcomponent
