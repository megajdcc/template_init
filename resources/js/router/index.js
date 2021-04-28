import Vue from 'vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter);

/****************************************/
/* Componentes
/****************************************/

// Home

import home from '../views/home.vue';

// Penfil de Usuario
import PerfilUsuario from '../views/admin/perfil.vue';

// Roles De usuarios
import Roles from '../views/admin/roles/roles.vue';

//Permisos de usuarios 
import PermisosUsuarios from '../views/admin/permisos/permisos.vue';

// Observaciones
import Observaciones from '../views/admin/observaciones/observaciones.vue';
import ListObservaciones from '../views/admin/observaciones/list.vue';


// Usuarios
import Usuarios from '../views/admin/usuarios/usuarios.vue';


const routes = [
		{ 	
				path:'/home', 
				name:'dashboard',
				component:home 
			},

			{ 
				path:'/perfil', 
				name:'perfil',
				component:PerfilUsuario 
			},
			
			{
				path:'/usuarios',
				name:'usuarios',
				component:Usuarios,
			},


			{ 
				path:'/configuracion/roles', 
				name:'configuracion.roles',
				component:Roles 
			},

			{ 
				path:'/configuracion/permisos', 
				name:'configuracion.permisos',
				component:PermisosUsuarios 
			},

			// OBservaciones
			{
				path:'/observaciones',
				component:Observaciones,

				children:[
					{
						path:'',
						name:'observaciones',
						component:ListObservaciones,
					},
				]	
			},




		




];



const router =  new VueRouter({
	mode:'history',
	routes,
	linkActiveClass:'active',
	scrollBehavior(to,from, savePosition){

		if(to.hash){
			return{
				selector:to.hash,
				behavior:'smooth'
			}
		}

		
	}

});


export default router;


