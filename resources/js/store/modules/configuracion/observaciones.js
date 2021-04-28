export default{

	namespaced:true,

	state(){
		return{
			observacion:{
				id        :null,
				modulo    :'',
				comentario:'',
				adjunto   :null,
				usuario_id:null,
				usuario   :null,
				atendido  :false
			},

			observaciones:[]

		}

	},

	getters:{

		draft(state){
			return clone(state.observacion);
		}

	},


	mutations:{

		setObservaciones(state,observaciones){
			state.observaciones = observaciones;
		},

		clearObservacion(state){
			state.observacion = {
				id        :null,
				modulo    :'',
				comentario:'',
				adjunto   :null,
				usuario_id:null,
				usuario   :null,
				atendido  :false
			}

		},

		pushObservacion(state,observacion){
			state.observaciones.push(observacion);
		},

		toggleAtender(state,observacion_id){
			var i = state.observaciones.findIndex((observacion) => observacion.id == observacion_id);

			if(i != 1){
				state.observaciones[i].atendido =!state.observaciones[i].atendido;
			}


			if(state.observacion.id){
				state.observacion.atendido = !state.observacion.atendido;
			}
		},


		capturarObservacion(state,observacion_id){

			state.observacion = state.observaciones.find(observacion => observacion.id == observacion_id);

		},


		putObservacion(state,observacion_id){
			var i = state.observaciones.findIndex((observacion) => observacion.id == observacion_id);
			state.observaciones.splice(i,1);
		}



	},

	actions:{

		cargarObservaciones({state,commit}){

			axios.get('/cargar/observaciones').then(respon => {
				commit('setObservaciones',respon.data);
			}).catch(e => {
				console.log(e);
			});
		
		},


		atendido({state,commit},observacion_id){


			commit('toggleAtender',observacion_id);

			axios.get('/observaciones/'+observacion_id+'/marcar/atendido').then(respon => {
				
				if(!respon.data.success){
					commit('toggleAtender');
				}

			}).catch(e => {
				commit('toggleAtender');
			});

		},

		async guardarObservacion({state,commit},data){
			return await axios.post('/observaciones',data,{
				headers:{
					'Content-Type': 'multipart/form-data'
				}
			});
		},

		async delete({state,commit},id){
			return await axios.delete('/observaciones/'+id);
		}

	
	}

}