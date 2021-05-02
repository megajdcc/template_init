<template>
		<div style="position:relative">
		
				<transition name="fadeoup">
					<keep-alive>
						<router-view></router-view>
					</keep-alive>
				</transition>
				<!-- <chat-dialog :showChat="showChat" @cerrarChat="showChat = false"></chat-dialog> -->

		</div>

</template>

<script>
	
	import { mapState, mapActions,mapMutations, mapGetters } from 'vuex'


export default{

	data(){
		return{
			showChat:false,
		   showDialog:false,
         chat_open:false,
         geo:null,
      }
	},


	created(){			

		this.cargarUsuario().then(result => {
			
				this.cargarUser(result.data);
				// this.setChats(this.usuario.chats);
				this.cargarNotificaciones(this.usuario.id);
				// this.ListenUsuario();
				this.cargarConfiguracion();
				this.cargarAlbumes();

				this.cargarVentas(this.usuario.id);
				
				if(this.conPermiso('Gestionar roles y permisos')){
					this.cargarRoles();
					this.cargarPermisos();
				}

				if(this.conPermiso('Gestionar usuarios')){
					this.cargarUsuarios();
				}

				if(this.conPermiso('Gestionar observaciones')){
					this.cargarObservaciones();
				}

				

		}).catch(e => {
			console.log(e);
		});
	},

   mounted(){

   },

	computed:{

		...mapState(['loading']),
		...mapState('usuario',['usuario'])

	},

	methods:{

		...mapMutations('usuario',['cargarUser']),
		...mapActions('observaciones',['cargarObservaciones']),
		...mapMutations('observaciones',['pushObservacion']),
		...mapMutations('chat',['companeroDesconectado','companeroConectado','setChats','AgregarMensaje','pushChat','setChat']),
		...mapActions('chat',['cargarCompaneros','capturarChat']),
		...mapActions('notificacion',['cargarNotificaciones']),
		...mapActions('permiso',['cargarPermisos']),
		...mapActions('usuario',['cargarUsuario']),
		...mapActions('rol',['cargarRoles']),
		...mapActions('usuario',['cargarUsuarios']),
		...mapActions('configuracion',['cargarConfiguracion']),
		...mapActions('album',['cargarAlbumes']),
		...mapActions('venta',['cargarVentas']),

      newNotification(){
         let audio_midi = new Audio('/storage/midi.mp3');
         audio_midi.play();
      },

   	conPermiso(permiso){
   			
   			var name_permiso = this.usuario.roles[0].permissions.find((permission) => permission.name == permiso);
   			
   			if(name_permiso != undefined){
   				return true;
   			}
   			
   			return false;
		},


	},


	watch:{
         



   }

}

</script>

<style>

	
	.fadeoup-leave-active,fadeoup-enter-active{

		transition:all .5s ease;	
      position:absolute;
	}

	.fadeoup-enter, 
	.fadeoup-leave-to{

		opacity:0;
	}

</style>

