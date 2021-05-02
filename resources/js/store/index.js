import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

// Import Moduldes

import usuario from './modules/usuario';

//Observaciones

import observaciones from './modules/configuracion/observaciones.js';

// Configuracion 

import rol from './modules/configuracion/rol.js';

import permiso from './modules/configuracion/permiso.js';


// Notificaciones
// 
import notificacion from './modules/notificaciones.js';

// Chat 

import chat from './modules/chat.js';


// Configuracion
// 
import configuracion from './modules/configuracion/configuracion.js';


// Albumes y Fotos

import album from './modules/album/album.js';
import foto from './modules/album/foto.js';

// Ventas

import venta from './modules/venta/venta.js';


// Tableros 

import AdminTablero from './modules/tableros/AdminTablero.js';
import UserTablero from './modules/tableros/UserTablero.js';
/***************************************************/

export default new Vuex.Store({
	strict:true,
	state:{
		errors:{},
		loading:false,
	},

	mutations:{

		cerrarApp(state){
			state.usuario = null
		},

		toggleLoading(state){
			state.loading = !state.loading
		},



		setError(state,data){
			state.errors = data
		},

		clearErrors(state){

			state.errors = {
				carta:{}
			}
			
		}

	},

	actions:{

		cerrarSesion({state,commit}){
			
			axios.post('/logout',this.user).then(resp => {
				commit('cerrarApp');
			}).catch(error => {
				console.log(error);
			}).then(() => {
				location.reload();
			});
			 
		},


	},

// Modulos

modules:{
		usuario,
		rol,
		notificacion,
		chat,
		observaciones,
		permiso,
		configuracion,
		album,
		foto,
		venta,
		'admin-tablero':AdminTablero,
		'user-tablero':UserTablero
		
},

});