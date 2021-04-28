export default{


	namespaced:true,

	state(){
		return{
			notificacion:{},
			todas:[],
			leidas:[],
			sinleer:[],
		}

	},


	getters:{
	
		cantidad(state){
			return state.sinleer.length;
		}
	},


	mutations:{

		setNotificacion(state,data){

			state.notificacion = data;
		},


		setNotificaciones(state,{leidas,sinleer,todas}){

			state.todas = todas;
			state.sinleer = sinleer;
			state.leidas = leidas;

		},

		pushLeida(state,notificacion){
			var index = state.sinleer.findIndex(noti => noti.id == notificacion.id);
			state.sinleer.splice(index,1);

			state.leidas.push(notificacion);
		},

		pushSinleer(state,notificacion){

			var index = state.leidas.findIndex(noti => noti.id == notificacion.id);

			state.leidas.splice(index,1);

			state.sinleer.push(notificacion);
		},

		pushNotificacion(state, notificacion){
			state.todas.push(notificacion);
		}
		

	},



	actions:{

		cargarNotificaciones({state,commit}, usuario){
			axios.get(`/notificaciones/${usuario}`).then(respon => {
				commit('setNotificaciones', {leidas:respon.data.leidas,sinleer:respon.data.sinleer,todas:respon.data.notificaciones});
			}).catch(e => {
				console.log(e);
			})

		},


		async marcarLeida({state,commit},{usuario,notificacion}){
			return await axios.get(`/notificaciones/markread/${notificacion}/usuario/${usuario}`);
		}


	}



}