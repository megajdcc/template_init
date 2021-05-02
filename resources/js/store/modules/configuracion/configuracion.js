export default{
	namespaced:true,


	state(){
		return{
			configuracion:{
				id               :null,
				paypal_id        :'',
				paypal_secret    :'',
				production_paypal:false,
				marca_agua       :''
			}
		}
	},


	getters:{
		draft(state){
			return clone(state.configuracion);

		}

	},

	mutations:{

		setConfiguracion(state,configuracion){
			state.configuracion = configuracion;
		},

	},


	actions:{
		
		cargarConfiguracion({state,commit}){

			axios.get('/cargar/configuracion').then(respon => {
				commit('setConfiguracion',respon.data);
			}).catch(e => {
				console.log(e);
			});

		},

		async guardarConfiguracion({state,commit},data){
			return axios.put(`/configuracion/configuracions/${state.configuracion.id}`,data);
		},





	}

}
