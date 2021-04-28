<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
         Schema::defaultStringLength(191);
            
         Blade::component('components.alert','alert');

         Blade::if('isroute', function($ruta){

                 if(is_array($ruta)){

                     $result = false;

     
                     foreach ($ruta as $key => $value) {
                            
                             if(Route::currentRouteName() == $value) {
                                 return true;
                             }
  
                    }

                     return $result;
                 }else{
                     return "<?php if(Request::url() == route($ruta)): ?>";
                 }
         });
        Route::pattern('id', '[0-9]+');
    }
}
