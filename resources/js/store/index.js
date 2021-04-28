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
		permiso
		
},

});