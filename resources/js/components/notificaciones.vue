<template>

<div style="width:60px">
		<el-badge :value="cantidad" class="item mx-2" >
			<button @click="drawer = true"  type="button" class="btn btn-outline-primary btn-sm border-0" title="Notificaciones" data-toggle="tooltip">
				<i class="fas fa-bell" style="font-size: 14pt"></i>
			</button>
		</el-badge>
	
	

	<el-drawer title="Notificaciones" :visible.sync="drawer" :modal="false" :direction="direccion" :before-close="close" size="320px">
					
				<el-card v-if="cantidad == 0" class="mx-2">
						<h5>No hay ninguna notificación por leer.</h5>
				</el-card>
					<div class="container" style="overflow-y: auto; max-height: 90vh; padding: 0 .5rem 0 0; text-align:justify">
						<el-timeline :reverse="reverse" style="padding:.5rem 1rem; max-height:90vh">
							<el-timeline-item 
								v-for="(notification,index) in sinleer"
								v-if="notification.read_at == null"
								:key="index"
								:timestamp="getFecha(notification.created_at)"
								icon="el-icon-bell text-black"
								placement="top"
								type="primary"
								color="#4AC5E0"
								size="large">	

									<el-card style="cursor:pointer" shadow="hover">
										<div slot="header" class="clearfix">
											<p  @click="mostrar(notification)" class="font-weight-bold text-primary text-left my-0"   v-html="notification.data.titulo"></p>
										</div>
										<p @click="mostrar(notification)">{{ notification.data.mensaje[0].substring(0,50) }}... <strong>(seguir leyendo)</strong></p>
									</el-card>

							</el-timeline-item>
						</el-timeline>
					</div>

	</el-drawer>

	<el-dialog :title="dnotification.data.titulo" 
					:visible.sync="showNotification"
					width="50%"
					:close-on-click-modal="true"
					:modal="false"
					:show-close="true"
					@close='cerrarNotification'
					>

						<p v-for="mensaje in dnotification.data.mensaje">{{ mensaje }}</p>

						<div class="btn-group d-flex justify-content-end">
							<button type="button" class="btn btn-outline-danger" @click.prevent="cerrar()">Cerrar Notificación</button>
							<button type="button" class="btn btn-outline-primary" @click.prevet="irAction()" v-if="isBtnAction">{{ dnotification.data.btnTitle }}</button>
							<button type="button" class="btn btn-outline-dark" @click="leida()"><i class="fas fa-eye mr-2"></i>Marcar como leida</button>
							<!-- <a v-if="dnotification.data.btn" :href="dnotification.data.url" :title="dnotification.data.btnTitle" class="btn btn-outline-primary">{{ dnotification.data.btnTitle }}</a> -->
						</div>
	</el-dialog>


</div>
	

</template>


<script>
	
	import {mapState, mapGetters, mapActions, mapMutations} from 'vuex';


	export default {
			name:'notificaciones',
		
			data(){
				return {
					drawer:false,
					direccion:'rtl',
					reverse:true,
			
					showNotification:false,
					dnotification:{
						id:0,
						data:{
							titulo:'',
							mensaje:'',
							btn:false,
							btnTitle:'',
							url:''
						},

					},
				}

			},
			
			created(){
			},
			
			computed:{
				...mapState('usuario',['usuario']),
				...mapState('notificacion',['todas','sinleer','leidas']),
				...mapGetters('notificacion',['cantidad']),

				isBtnAction(){
					return (this.dnotification.data.btn);
				}


			},


			methods:{

				...mapActions('notificacion',['marcarLeida']),
				...mapMutations('notificacion',['pushLeida']),

				close(done){
					done();
				},
				
				cerrar(){
					this.showNotification = false
				},

				getFecha(fecha){
					return moment(fecha).format('LLL')
				},

				mostrar(notification){
					this.dnotification = notification
				
					this.showNotification = true
				},

				irAction(){

						this.cerrar();
						
						if(this.dnotification.data.url.params){
							this.$router.push({name:this.dnotification.data.url.name,params:this.dnotification.data.url.params});
						}else{
							this.$router.push({name:this.dnotification.data.url.name});
						}

				},

				cerrarNotification(){
					this.cerrar();
				},


				leida(){
					this.marcarLeida({usuario: this.usuario.id, notificacion:this.dnotification.id}).then(respo => {
							if(respo.data.success){
								this.$notify({
                           message: 'Notificación leída',
                           duration:'3000',
                           type:'info',
                           position:'top-left',
                         })
								
								this.pushLeida(respo.data.notificacion);
								this.cerrar();
							}

					}).catch(error =>{
								console.log(error)
					}).then(()=>{
						this.showNotificacion = false
					});
				}

			},


	}

</script>

<style lang="scss" scope>
	.el-drawer__header{
		margin-bottom:12px !important;
	}

	.el-card__header{
		height:auto !important;
	}
</style>