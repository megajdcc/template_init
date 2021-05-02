<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Permiso, Rol, User};
class PermisosUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $permisos = ['Ver tienda de fotos','Ver mis compras'];

         $permisos_registrados = collect([]);

         foreach ($permisos as $key => $value) {
             $permisos_registrados->push(Permiso::create(['name' => $value]));        
         }

         $rol = Rol::create(['name' => 'User']);

         $rol->givePermissionTo($permisos_registrados);
    }
}
