
export default{

	namespaced:true,

	state(){
		return{
			rol:{
				name    :null,
				id      :null,
				permisos:[],
			},
			roles:[],

		}
	},

	mutations:{

		setRol(state,data){
			state.rol = {
						name    :data.name,
						id      :data.id,
						permisos:data.permis,
				}
		},

		clearRol(state){
			state.rol = {
				name    :null,
				id      :null,
				permisos:[],
			}

		},

		capturarRol(state,id_rol){
			state.rol = state.roles.find((rol) => rol.id == id_rol);
		},

		setRoles(state,data){


			data.forEach((rol,i) => {
				state.roles.push({
						name    :rol.name,
						id      :rol.id,
						permisos:rol.permissions,
				})
			})

		
		},

		pushRol(state,role){

			if(state.roles.find((rol) => rol.id == role.id)){

				state.roles.forEach((rol,i) => {
					if(rol.id == role.id){
						state.roles[i] = {
									name    :role.name,
									id      :role.id,
									permisos:role.permis,
							}
					}

				})
			}else{
				state.roles.push({
						name    :role.name,
						id      :role.id,
						permisos:role.permis,
				});
			}

		},

		putRol(state,id_rol){

			var index = state.roles.findIndex((rol) => rol.id == id_rol);

			if(index) {
				state.roles.splice(index,1);
			}

		}

	},


	getters:{


		draft(state){
			return clone(state.rol);
		},

		

	},

	actions:{

		cargarRoles({commit}){
			commit('toggleLoading',null,{root:true});
			axios.get('/listar/roles').then(respon => {

				commit('setRoles',respon.data);
			}).catch(e => {
				console.log(e)
			}).then(() => {
				commit('toggleLoading',null,{root:true});
			});
			

		},


		async guardarRol({state,commit},data){

			if(state.rol.id){
				return await axios.put(`/configuracion/roles/${state.rol.id}`,data);
			}else{
				return await axios.post(`/configuracion/roles`,data);
			}
		},


		async eliminarRol({state,commit},id_rol){
			return await axios.delete(`/configuracion/roles/delete/${id_rol}`);
		}





	}
}