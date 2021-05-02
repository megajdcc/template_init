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

// Configuracion
import Configuracion from '../views/admin/configuracion/configuracion.vue';

// Albumes y fotos 
import Albumes from '../views/admin/albumes/albumes.vue';
import ListAlbumes from '../views/admin/albumes/list.vue';
import CreateAlbum from '../views/admin/albumes/create.vue';
import EditAlbum from '../views/admin/albumes/edit.vue';
import ShowFotos from '../views/admin/albumes/fotos.vue';



// Tienda de fotos
import tienda from '../views/tienda.vue';
import carrito from '../views/carrito.vue';
import CajaTienda from '../views/CajaTienda.vue';

// Mis Compras
import MisCompras from '../views/MisCompras.vue';
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


			// Albumes y fotos
			{
				path:'/albums',
				component:Albumes,
				children:[
					{
						path:'',
						name:'albumes',
						component:ListAlbumes,
					},

					{
						path:'create',
						name:'albumes.create',
						component:CreateAlbum,
					},

					{
						path:':id/edit',
						props:true,
						name:'albumes.edit',
						component:EditAlbum,
					},

					{
						path:':id/show/fotos',
						props:true,
						name:'albumes.show.fotos',
						component:ShowFotos,
					},


				]	
			},


			// Configuracion
			{
				path:'/configuracion/configuracions',
				component:Configuracion,
				name:'configuracion',
			},

			// Tienda

			{
				path:'/tienda',
				component:tienda,
				name:'tienda',
			},

			{
				path:'/carrito',
				component:carrito,
				name:'carrito',
			},

			{
				path:'/tienda/caja',
				component:CajaTienda,
				name:'cajatienda',
			},

			{
				path:'/compras',
				component:MisCompras,
				name:'compras',
			}



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


