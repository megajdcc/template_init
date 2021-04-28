export default{


	namespaced:true,

	state(){
		return{
			companeros:[],
			chat:{},
			usuario:{},
			chats:[],
			
			redaccion:{
				mensaje:'',
		      usuario_id:null,
		      chat_id:null,
			}


		}
	},


	getters:{

		conectados:(state) => {

			return (usuario) => {

					return state.companeros.filter((companero) => (companero.conectado && companero.id != usuario.id)).length;
				
			}
			
		},


		companeros:(state) => {
			return (usuario) => {
					return state.companeros.filter((companero) => companero.id != usuario.id);
			}
		
		},


	},

	mutations:{

		setCompaneros(state,data){
			state.companeros = data
		},

		unirme(state,usuario_id){
			state.redaccion.usuario_id = usuario_id;
			state.redaccion.chat_id = state.chat.id;
		},

		limpiarRedaccion(state){
				state.redaccion.mensaje = '';
		},


		companeroDesconectado(state,usuario){

			state.companeros.forEach((companero,i) => {

				if(companero.id === usuario.id){
					state.companeros[i].conectado = false;
				}

			});
		},

		companeroConectado(state,id){

			state.companeros.forEach((companero,i) => {

				if(companero.id === id){
					state.companeros[i].conectado = true;
				}

			});

		},

		setChats(state,data){
			state.chats = data;
		},

		pushChat(state,chat){
			state.chats.push(chat);
		},


		setChat(state,chat){
			state.chat = chat
		},

		cerrarChat(state){
			state.chat = {}
		},

		pushMensaje(state,mensaje){
			state.chat.mensajes.push(mensaje);
		},

		AgregarMensaje(state,{chat_id,mensaje}){

			state.chats.forEach((chat,i) => {
				if(chat.id == chat_id){
					state.chats[i].mensajes.push(mensaje);
				}
			})

		}


	},

	actions:{

		cargarCompaneros({state,commit}){


			commit('toggleCarga',null,{root:true});
			axios.get(`/chat/get/companeros`).then(respon => {
				commit('setCompaneros',respon.data);
			}).catch(e => {
				console.log(e);
			}).then(() => {
			commit('toggleCarga',null,{root:true});
			});

		},


		capturarChat({state,commit, dispatch},{user_actual, user_related}){

			if(state.chats.length > 0 ){

				var chat = 	state.chats.find((chat) => {

						var user_1  = chat.usuarios.find((user) => user.id == user_actual);
						var user_2  = chat.usuarios.find((user) => user.id == user_related);
						return (user_1 && user_2)

					})

				if(chat){
					commit('setChat',chat);
				}else{
					dispatch('crearChat',{user_actual:user_actual, user_related:user_related});
				}


			}else{
					dispatch('crearChat',{user_actual:user_actual, user_related:user_related});
			}

		},

		crearChat({state,commit},{user_actual, user_related} ){
				axios.post(`/chat/create/${user_actual}`,{user_related:user_related}).then(respon => {
					
					if(respon.data.success){
						// commit('pushChat',respon.data.chat);
						// commit('setChat',respon.data.chat);

					}else{
						return false;
					}

				}).catch(e => {

					console.log(e);

				});
		},

		enviarMensaje({state,commit},mensaje){


				axios.put(`/chat/${state.chat.id}/send/mensaje`,mensaje).then(respon => {

					if(respon.data.success){
						commit('pushMensaje',respon.data.mensaje);
						commit('limpiarRedaccion');
					}

				}).catch(e => {
					console.log(e);
				});
		}


	}

}
