<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Configuracion;

class ConfiguracionSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $configuracion = Configuracion::create([
                'paypal_id'         =>  '',
                'paypal_secret'     =>  '',
                'production_paypal' => false,
                'marca_agua'        => null,
        ]);
    }
}
