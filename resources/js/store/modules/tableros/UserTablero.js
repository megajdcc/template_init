export default{

	namespaced:true,


	state(){
		return{
			total_compras:0,
			fotos_compradas:0,
			cantidad_albumes:0,
		}

	},

	mutations:{
		
		setTablero(state,data){
			state.total_compras      = data.total_compras
			state.fotos_compradas    = data.fotos_compradas;
			state.cantidad_albumes   = data.cantidad_albumes;

		},

		updateTablero(state,data){
			state.total_compras      = data.total_compras
			state.fotos_compradas    = data.fotos_compradas;
			state.cantidad_albumes   = data.cantidad_albumes;
		}

	},


	actions:{

		cargarTablero({state,commit}){
			commit('toggleLoading',null,{root:true});
			axios.get('cargar/tablero/user').then(respon => {
				commit('setTablero',respon.data);
			}).catch(e => {
				console.log(e);
			}).then(() => {
				commit('toggleLoading',null,{root:true});
			});
		}

	}








}