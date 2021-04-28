<template>
<div style="width:60px">
		<el-badge :value="cantidadConectado" class="item mx-2" >
			
			<button @click="drawer = true"  type="button" class="btn btn-outline-primary btn-sm border-0" title="Chat" data-toggle="tooltip">
				<i class="fas fa-users" style="font-size: 14pt"></i>
			
			</button>

		</el-badge>

	<el-drawer :visible.sync="drawer" :modal="false" :direction="direccion" :before-close="close" :withHeader="false" size="320px">
				
				<slot name="title">
					<h2 class="ml-3 mt-2"><i class="fas fa-users mx-2" style="font-size: 14pt"></i>Compañeros</h2>
				</slot>
				
				<div class="container companeros-container" v-if="usuario">
											
					<!-- Companeros -->

					<el-divider>Compañeros</el-divider>

					<el-divider>Conectados</el-divider>
					<ul class="list-group w-100">
						<li class="list-group-item elevation-2 shadow-sm d-flex align-items-center mb-2" v-for="user in usuarios" :key="user.id" @click="abrirChat(user)" v-if="user.conectado">
							
							<figure class="profile-picture img-perfil my-0" :class="{'user-conectado' : user.conectado}">
                           <img :src="getAvatar(user.imagen)" :alt="user.nombre+' '+user.apellido"  class="rounded-circle" style="width:40px; height:40px"/>
                     </figure>
							
							<el-divider content-position="left">{{ (user.nombre) ? user.nombre+' '+user.apellido : user.username }}</el-divider>

						</li>
					</ul>

					<el-divider>No conectados</el-divider>

					<ul class="list-group w-100">
						<li class="list-group-item elevation-2 shadow-sm d-flex align-items-center mb-2" v-for="user in usuarios" :key="user.id" @click="abrirChat(user)" v-if="!user.conectado">
							
							<figure class="profile-picture img-perfil my-0" :class="{'user-conectado' : user.conectado}">
                           <img :src="getAvatar(user.imagen)" :alt="user.nombre+' '+user.apellido"  class="rounded-circle" style="width:40px; height:40px"/>
                     </figure>
							
							<el-divider content-position="left">{{ (user.nombre) ? user.nombre+' '+user.apellido : user.username }}</el-divider>

						</li>
					</ul>
				</div>
	</el-drawer>

	<chat-dialog :showChat="showChat" @cerrarChat="showChat = false"></chat-dialog>

</div>
	

</template>


<script>

import {mapState, mapGetters, mapActions, mapMutations} from 'vuex';
import ChatDialog from './ChatDialog.vue';


export default {

  name: 'chat-user',
  components:{
  	ChatDialog
  },

  data () {
    return {
			
			drawer:false,
			direccion:'rtl',
			reverse:true,
    		showChat:false,
    }
  },

  computed:{
  		...mapState('usuario',['usuario']),
  		...mapGetters('chat',['conectados','companeros','usersInfochannel']),

  		usuarios(){

  			if(this.usuario){
  				return this.companeros(this.usuario);
  			}else{
  				return [];
  			}

  		},


  		cantidadConectado(){

  			if(this.usuario){
  				return this.conectados(this.usuario);
  			}else{
  				return 0;
  			}

  		}

  },


  methods:{
  	...mapActions('chat',['capturarChat']),

  	close(done){
  		done();
  	},

  	getAvatar(avatar){

  		return (avatar) ? '/storage/img-perfil/'+avatar : '/storage/img-perfil/default.jpg';

  	},


  	abrirChat(user){


  		this.$eventHub.$emit('chatOpenUpdate',true);

  		this.capturarChat({user_actual:this.usuario.id, user_related: user.id}).then(result => {
  			this.showChat = true;
  		});

  		
 
  	}

  }

}
</script>

<style lang="scss" scoped>

	.companeros-container{
		overflow-y: auto; 
		max-height: 90vh; 
		padding: 0 .5rem 1rem .5rem; 
		perspective:1000px;

		.list-group{
			transform-style:preserve-3d;

			.list-group-item{
				cursor:pointer;
				transition: all 1s ease;
			}
		
		}

		.list-group .list-group-item:hover{
				transform:traslateZ(75px);
		}
	}

	.profile-picture{
		position:relative;
	}
	
	.user-conectado::after{
		content:"\f111";
		position:absolute;
		color:#01ff70;
		border-radius: 100%;
		width:15px;
		height:15px;
		background-color:#01ff70;
		right:-6px;
		top:-3px;
	}


</style>