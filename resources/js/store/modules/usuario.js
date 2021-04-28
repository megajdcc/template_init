export default {

	namespaced:true,

	state:() => ({
			usuario:{
				id              :null,
				nombre          :'',
				apellido        :'',
				telefono        :'',
				fecha_nacimiento:'',
				imagen          :'',
				direccion       :'',
				email           :'',
				password        :'',
				rol_id          :'',
				rol             :null,
				avatar          :'',
			},

			user:{
				id              :null,
				nombre          :'',
				apellido        :'',
				telefono        :'',
				fecha_nacimiento:'',
				imagen          :'',
				direccion       :'',
				email           :'',
				password        :'',
				rol_id          :'',
				rol             :null,
				avatar          :'',
			},

			usuarios:[],

		}),

	mutations:{
	

		cargarUser(state,data){			
	
			state.usuario = data;


		},

		setTelefono(state,numero){

			state.usuario.telefono = numero
		},


		setUsuarios(state,usuarios){
			state.usuarios = usuarios;
		},

		pushUsuario(state,usuario){
			state.usuarios.push(usuario);
		},

		capturarUsuario(state,id_usuario){

			state.user = state.usuarios.find((user) => user.id == id_usuario)
		},

		clearUsuario(state){
			state.user = {id              :null,
									nombre          :'',
									apellido        :'',
									telefono        :'',
									fecha_nacimiento:'',
									imagen          :'',
									direccion       :'',
									email           :'',
									password        :'',
									rol_id          :'',
									rol             :null,
									avatar          :''}
		},


		updateUsuario(state,data){

			var i = state.usuarios.findIndex((user) => user.id == data.id);

			if(i != -1){
				state.usuarios[i] = data;
				state.user = data;
			}



		},



		update(state,data){

					var i = state.usuarios.findIndex((user) => user.id == data.id);

			if(i != -1){
				state.usuarios[i] = data;
				state.usuario = data;
			}
		},

		updateAvatar(state,avatar){
			state.usuario.avatar = avatar;
		}

	},

	getters:{

		draft(state){
			return clone(state.user);
		},

		conPermiso:(state) => {
			return (permiso) => {

				if(state.usuario){
					return (state.usuario.roles[0].permissions.find((permission) => permission.name == permiso))
				}

				return false;
				
			}
		},


		getUsuarios: (state) => {
			return (rol) => {
				return state.usuarios.filter(user => {
				
					if(user.rol){
						return (user.rol.name == rol);
					}else{
						return false;
					}

				});
			} 
		},


		getUsuario:(state) => {
			return (id) => {
				return state.usuarios.find(user => user.id ==  id);
			}
		},


		getListado:(state) => {
			return (users_id) => {

			

				var users = [];

				users_id.forEach((e,i) => {

					var us = state.usuarios.find((u) => {

					 return (u.id == e.user_id);
					})

					if(us != undefined){
						if(users.find((u) => u.id == us.id)  == undefined){
							users.push(us);
						}
						
					}
					
				});

				console.log(users);

				return users;

			}
		},





	},

	actions:{

		


		cargarUsuarios({state,commit}){
			var result = false;

			commit('toggleLoading',null,{root:true});

			axios.get('/usuarios/all').then(respon => {
					result = true;
					commit('setUsuarios',respon.data);
			}).catch(e => {
				console.log(e)
			}).then(() => {
				commit('toggleLoading',null,{root:true});
			})

			return result;
		},

		async cargarUsuario({state,commit,dispatch}){

				return await axios.get('/app/get/data');

		},


		async guardar({state,commit,dispatch},data){
			
			if(state.user.id){
				return await axios.put(`/usuarios/`+state.usuario.id, data);
			}else{
				return await axios.post('/usuarios',data);
			}
	   
		},

		async guardarUsuario({state,commit,dispatch},draft){
			return await axios.put(`/perfil/update/usuario/`+state.usuario.id, draft);
		},




	}




} 
