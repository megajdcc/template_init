<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Permiso, Rol, User};
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

            $permisos = ['Gestionar usuarios','Ver configuracion','Gestionar roles y permisos'];

            $permisos_registrados = collect([]);

            foreach ($permisos as $key => $value) {
                $permisos_registrados->push(Permiso::create(['name' => $value]));        
            }

    		$rol = Rol::create([
    			'name' => 'Super Administrador'
    		]);


            $rol->givePermissionTo($permisos_registrados);

    		$usuario = User::create([
				'nombre'   => 'Jhonatan Deivyth',
				'apellido' => 'Crespo Colmenarez',
				'telefono' => '+584128505504',
				'rol_id'   => $rol->id,
				'email' => 'megajdcc2009@gmail.com',
				'password' => Hash::make('20464273jd'),
                'is_password' => true
    		]);

    		$usuario->assignRole($usuario->rol_id);
    		$usuario->save();
    }
}
