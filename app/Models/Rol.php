<?php

namespace App\Models;

use Spatie\Permission\Models\Role;

class Rol extends Role
{
    
	
	public function usuarios(){
		return $this->hasMany('App\Models\User','rol_id','id');
	}

	public function permisos(){
		return $this->belongsToMany('App\Models\Permiso','role_has_permissions','role_id','permission_id');
	}

}
