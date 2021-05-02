export default{

	namespaced:true,
	state(){
		return{
			
			venta:{
					id             :null,
					monto          :0,
					fotos          :null,
					pagado         :false,
					respon_paypal  :null,
					condicion_envio:1,
					email_envio    :'',
					comprador_id   :null,
					url_zip:'',
					comprador      :null
			},

			ventas:[]

		}

	},


	getters:{
		draft(state){
			return clone(state.venta)
		}
	},

	mutations:{

		setVentas(state,ventas){
			state.ventas = ventas;
		},

		clearVenta(state,venta){
			
			state.venta = {
					id             :null,
					monto          :0,
					fotos          :null,
					pagado         :false,
					respon_paypal  :null,
					condicion_envio:1,
					email_envio    :'',
					comprador_id   :null,
					url_zip:'',
					comprador      :null
			};

		},


		pushVenta(state,venta){
			state.ventas.push(venta);
		}


	},

	actions:{

		cargarVentas({commit,state},usuario_id){

			axios.get(`/usuario/${usuario_id}/cargar/ventas`).then(respon => {

				commit('setVentas',respon.data);
			
			}).catch(e => {
			
				console.log(e);
			
			});

		},


		async generar({state,commit},data){
			return await axios.post(`/ventas`,data);
		}


	}

}