@extends('adminlte::master')

@section('adminlte_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body', 'register-page')

@php( $login_url = View::getSection('login_url') ?? config('adminlte.login_url', 'login') )
@php( $register_url = View::getSection('register_url') ?? config('adminlte.register_url', 'register') )
@php( $dashboard_url = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home') )

@if (config('adminlte.use_route_url', false))
    @php( $login_url = $login_url ? route($login_url) : '' )
    @php( $register_url = $register_url ? route($register_url) : '' )
    @php( $dashboard_url = $dashboard_url ? route($dashboard_url) : '' )
@else
    @php( $login_url = $login_url ? url($login_url) : '' )
    @php( $register_url = $register_url ? url($register_url) : '' )
    @php( $dashboard_url = $dashboard_url ? url($dashboard_url) : '' )
@endif

@section('body')
    <div class="register-box">
        <div class="register-logo">
            <a href="{{ $login_url }}" style="width: 200px; height: auto; max-width: 200px;">
                <img src="{{ asset(config('adminlte.logo_img_xl')) }}" alt="{{ env('APP_NAME') }}" >
            </a>
        </div>
        <div class="card">
            <div class="card-body register-card-body">
            <p class="login-box-msg">Establecer contraseña a su cuenta</p>
             <form method="POST" action="{{ route('establecercontrasena',['usuario' => $usuario]) }}">
               @csrf
               @method('put')
                
                <div class="input-group mb-3">
                   
								<input type="password" name="contrasena" class="form-control @error('contrasena') is-invalid @enderror" placeholder="Contraseña" value="{{ old('contrasena') }}">

								<div class="input-group-append">
									<span class="input-group-text"><i class="fas fa-lock"></i></span>
								</div>
									
								@error('contrasena')
		                     <span class="invalid-feedback" role="alert">
		                           <strong>{{ $message }}</strong>
		                     </span>
	                     @enderror
							
                </div>


                <div class="input-group mb-3">
						
								<input type="password" name="repetir-contrasena" class="form-control @error('repetir-contrasena') is-invalid @enderror" placeholder="Vuelva a  escribir la contraseña" value="{{ old('contrasena') }}" >

								<div class="input-group-append">
									<span class="input-group-text"><i class="fas fa-lock"></i></span>
								</div>
									
								@error('repetir-contrasena')
		                     <span class="invalid-feedback" role="alert">
		                           <strong>{{ $message }}</strong>
		                     </span>
	                     @enderror
							
                </div>
                <button type="submit" class="btn btn-primary btn-block btn-flat">
                    {{ __('Guardar') }}
                </button>
            </form>

        </div>
        <!-- /.form-box -->
    </div><!-- /.register-box -->
@stop

@section('adminlte_js')
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    @stack('js')
    @yield('js')
@stop
