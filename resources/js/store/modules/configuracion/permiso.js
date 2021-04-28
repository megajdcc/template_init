export default{

	namespaced:true,

	state(){
		return{
			permiso:{
				id:null,
				name:'',
			},
			permisos:[],
		}
	},


	mutations:{

		setPermiso(state,permiso){
			state.permiso = permiso

		},

		setPermisos(state,permisos){

			state.permisos = permisos
		
		},


		clearPermiso(state){

			state.permiso = {
				id      :null,
				name  :'',
			}
		
		},

		capturarPermiso(state,id_permiso){
			state.permiso = state.permisos.find((permiso) => permiso.id === id_permiso);
		},


		putPermiso(state,id_permiso){

			state.permisos.splice(state.permisos.findIndex((permiso) => permiso.id == id_permiso),1);
		},

		pushPermiso(state,permiso){
			var permis = state.permisos.find((permis) => permis.id == permiso.id);

			if(permis){
				state.permisos.forEach((permis,i ) => {
					if(permis.id == permiso.id){
						state.permisos[i] = permiso
					}
				})
			}else{

				state.permisos.push(permiso);
			}

		}





	},

	getters:{

		draft(state){
			return clone(state.permiso);
		},

	},

	actions:{
		
		cargarPermisos({state,commit}){
			commit('toggleLoading',null,{root:true});
			axios.get('/cargar/permisos').then(respon => {

				commit('setPermisos',respon.data);

			}).catch(e => {

				console.log(e);
			}).then(() => {
				commit('toggleLoading',null,{root:true});
			})


		},


		async guardarPermiso({state,commit},data){

			if(state.permiso.id){
				return await axios.put('/configuracion/permisos/'+state.permiso.id,data);
			}else{
				return await axios.post('/configuracion/permisos',data);
			}
		},


		async eliminarPermiso({state,commit},id_permiso){

			return await axios.delete('/configuracion/permisos/'+id_permiso);
		}


	}

}
