export default{
	namespaced:true,

	state(){
		return {
			foto:{
				id            :null,
				nombre        :'',
				foto_con_marca:'',
				album_id      :null
			},

			fotos:[]
		}
		
	},

	getters:{
		
		draft(state){
			return clone(state.album);
		}

	},

	mutations:{
		
		setFoto(state,foto){
			state.foto = foto;
		},


	},

	actions:{

		async guardarFoto({state,commit},data){
			return await axios.post(`albums`,data);
		}

	}


}