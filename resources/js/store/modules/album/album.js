export default {

	namespaced:true,


	state(){
		return {
			album:{
				id:null,
				nombre:'',
    			usuario_id:null,
    			publicante:null,
    			fotos:[],
			},

			albumes:[]
		}

	},


	getters:{

		draft(state){
			return clone(state.album);
		},


		fotos:(state) => {
			return (id_album) => {
				let i = state.albumes.findIndex(album => album.id == id_album);
				return clone(state.albumes[i].fotos);
			}
		},


		getFotosAlbumes(state){

			let fotos = [];

			state.albumes.forEach(album => {
				album.fotos.forEach(foto => {
					fotos.push(foto);
				});
			});


			return fotos;
		},


		getNombreAlbum:(state) => {
			return (foto) => {

				let album = state.albumes.find(album => {
					return (album.fotos.find(fot => fot.id == foto.id) != undefined);
				});

				 return album.nombre;
			}

		}

		// getAlbumes:(state) => {
			
		// 	return (user_album) => {

		// 		let mis_fotos = [];

		// 		user_album.forEach((ele) => {
					
		// 			let album = state.albumes.find(album => album.id == ele);

		// 			if(album != undefined){

		// 			}


		// 		});

		// 		return mis_albumes;
		// 	}

		// }
	},


	mutations:{

		setAlbumes(state,albumes){
			state.albumes = albumes
		},

		setAlbum(state,album){
			state.album = album;
		},


		capturarAlbum(state,album_id){
			var i = state.albumes.findIndex(album => album.id === album_id);

			if(i != -1){
				state.album = state.albumes[i]
			}
		},


		clearAlbum(state){

			state.album = {
					id        :null,
					nombre    :'',
					usuario_id:null,
					publicante:null,
					fotos     :[],
			};

		},

		pushAlbum(state,album){
			state.albumes.push(album);
		},

		putAlbum(state,album_id){
			let i = state.albumes.findIndex(album => album.id == album_id);
			state.albumes.splice(i,1);
		},

		updateAlbum(state,album){

			let i = state.albumes.findIndex((albu) =>  albu.id == album.id)

			if(i != -1){
				state.albumes[i] = album
			}

			state.album = album;

		},

		pushFoto(state,foto){
			
			let i = state.albumes.findIndex((album) => album.id == state.album.id)

			if(i != -1){
				state.albumes[i].fotos.push(foto);
			}

			state.album.fotos.push(foto);

		},


		putFotoAlbum(state,foto){

			let i = state.album.fotos.findIndex(fot => fot.id == foto);
			if(i != -1){
				state.album.fotos.splice(i,1);
			}
		},


		pushFotoAlbum(state,foto){
			
			// state.album.fotos.push(foto);
			
			let  i = state.albumes.findIndex(album => album.id == state.album.id);

			if(i != -1){
				state.albumes[i].fotos.push(foto);
			}	
		},

		updateFotoAlbum(state,foto){

			let i = state.album.fotos.findIndex(fot => fot.id == foto.id);

			if(i != -1){
				state.album.fotos[i] = foto;
			}

		}






	},


	actions:{

		cargarAlbumes({state,commit}){

			commit('toggleLoading',null,{root:true});

			axios.get('/cargar/albums').then(respon => {
				commit('setAlbumes',respon.data);
			}).catch(e => {
				console.log(e);
			}).then(() => {
				commit('toggleLoading',null,{root:true});
			
			}) 

		},

		async guardar({state},data){

			if(state.album.id){
				return await axios.put(`/albums/${state.album.id}`,data);
			}else{
				return await axios.post(`/albums`,data);
			}

		},


		async renombrarAlbum({state,commit},album){
			commit('updateAlbum',album);

			commit('toggleLoading',null,{root:true});
			
			return await axios.put(`/albums/${album.id}/renombrar`,album).then(respon => {

				if(!respon.data.success){
					commit('updateAlbum',respon.data.album);
					commit('clearAlbum');
				}else{
						commit('clearAlbum');
				}

					commit('clearErrors',null,{root:true});

			}).catch(e => {
				
				if(e.response.status === 422){

					commit('setError',e.response.data.errors,{root:true});
				}else{
					console.log(e)
				}

			}).then(() => {
				commit('toggleLoading',null,{root:true});
			})

		},


		eliminar({state,commit},album_id){
			
			commit('toggleLoading',null,{root:true});
			
			commit('putAlbum',album_id);

			axios.delete(`/albums/${album_id}`).then(respon => {

				if(!respon.data.success){
					commit('pushAlbum',respon.data.album);
				}

			}).catch(e => {
				console.log(e);
			}).then(() => {
				commit('toggleLoading',null,{root:true});
			})


		},

		eliminarFotoAlbum({state,commit},foto_id){

			commit('toggleLoading',null,{root:true});
			commit('putFotoAlbum',foto_id);

			axios.delete(`/albums/${state.album.id}/eliminar/foto/${foto_id}`).then(respon => {

				if(!respon.data.success){
					commit('pushFotoAlbum',respon.data.foto);
				}

			}).catch(e => {
				console.log(e);
			}).then(() => {
				commit('toggleLoading',null,{root:true});
			})



		},

		async cambiarPrecioFoto({state,commit},foto){
			return await axios.put(`/albums/foto/${foto.id}/change/precio`,foto);
		}

	}
}