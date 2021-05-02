export default{

	namespaced:true,


	state(){
		return{
			total_ventas:0,
			cantidad_fotos:0,
			cantidad_albums:0,
			cantidad_usuarios:0,
		}

	},



	mutations:{
		
		setTablero(state,data){
			state.total_ventas      = Number(data.total_ventas);
			state.cantidad_fotos    = data.cantidad_fotos;
			state.cantidad_albums   = data.cantidad_albums;
			state.cantidad_usuarios = data.cantidad_usuarios;
		},


		updateTablero(state,data){

			state.total_ventas      = Number(data.total_ventas);
			state.cantidad_fotos    = data.cantidad_fotos;
			state.cantidad_albums   = data.cantidad_albums;
			state.cantidad_usuarios = data.cantidad_usuarios;
		
		}

	},


	actions:{

		cargarTablero({state,commit}){
			commit('toggleLoading',null,{root:true});
			axios.get('cargar/tablero/admin').then(respon => {
				commit('setTablero',respon.data);
			}).catch(e => {
				console.log(e);
			}).then(() => {
				commit('toggleLoading',null,{root:true});
			});
		}

	}








}