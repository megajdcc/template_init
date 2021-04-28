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
		
		ListenUsuario(){

				Echo.private('App.Models.User.'+this.usuario.id)
					.notification((notificacion) => {
                 
                   this.newNotification();

						this.$notify({
							title:notificacion.title,
							showClose:true,
							message:notificacion.mensaje,
							dangerouslyUseHTMLString:true,
							duration:0,
							type:'success',
							position:'bottom_right'
						});

						this.cargarNotificaciones(this.usuario.id);
				});
				var canal = 'chat';

   			Echo.join(`user-conected.${canal}`)
   					.here((users) => {
   						console.log(users)
   					})
   					.joining((user) => {
   						// console.log('joining - '+user.nombre);
   					})
   					.leaving((user) => {
   					})
   					.listen('UsuarioConectado' , (data) => {

   						this.companeroConectado(data.id);
							
							this.$notify({
								title:'Nuevo usuario conectado',
								dangerouslyUseHTMLString:true,
								message:`<b>${data.nombre.toUpperCase()} <br> ${data.panel} : ${data.procedencia}</b>`,
								showClose:true,
								iconClass:'el-icon-s-custom',
								duration:5000,
							})

                       this.newNotification();

						}).listen('UsuarioDesconectado' , (data) => {
							
							console.log(data);

							this.$eventHub.$emit('update_list_responsable',200);

							var panel = this.usuario.panel.panel.toLowerCase();

							var id_panel = null;

							if(panel == 'hotel'){
							
								id_panel = this.usuario.hotel.id; 
							
							}else if(panel == 'negocio'){
								 id_panel == this.usuario.negocio.id;
							}

							this.companeroDesconectado(data.usuario);
				});

				Echo.private(`nuevo-chat.${this.usuario.id}`)
   						.listen('NuevoChat', (data) => {
   							this.pushChat(data.chat)
   							this.setChat(data.chat);

   							Echo.join(`chat.${data.chat.id}`)
						          .listen(('ChatMensaje'), (data) => {
						            this.AgregarMensaje({chat_id:data.chat.id,mensaje:data.mensaje});

                              if(!this.chat_open){
                                 this.newNotification();
                                 this.$notify.info({
                                    title:'Nuevo mensaje de: '+data.mensaje.usuario.nombre+' '+data.mensaje.usuario.apellido,
                                    duration:8000,
                                    position:'bottom_left',
                                    showClose:true,
                                    message:'Clics en mí, para abrir chat',
                                    onClick:()=>{
                                          this.capturarChat({user_actual:this.usuario.id, user_related: data.mensaje.user_id}).then(result => {
                                             this.showChat = true;
                                          });
                                    }
                                 })
                              }

                              

						          })
						          .here((users) => {
						            //
						          })
						          .joining((user) => {
						           	this.$notify.info({
						          		title:'El usuario '+user.nombre+' '+user.apellido,
						          		message:'Se ha unido al chat.',
						          		dangerouslyUseHTMLString:true,
						          		duration:5000,
						          		position:'bottom_left'
						          	})

						          })
						          .leaving((user) => {
						          	this.$notify.info({
						          		title:'El usuario '+user.nombre+' '+user.apellido,
						          		message:'Ha abandonado el chat. <br> Todos los mensajes que escribas se lo haremos saber por correo',
						          		dangerouslyUseHTMLString:true,
						          		duration:8000,
						          		position:'bottom_left'
						          	})
						          });
				});

				Echo.channel('observacion-module')
		      .listen('ObservacionModulo', (data) => {
		         this.pushObservacion(data.observacion);

		         this.$notify({
		            title:'Nueva Observación en modulo',
		         });
		           this.newNotification();

		         setTimeout(() => {
		             this.cargarNotificaciones(this.usuario.id);
		         },3000);

		      });		   	
		},

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

