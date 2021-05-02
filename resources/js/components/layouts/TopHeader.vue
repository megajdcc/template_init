<template>
						<!-- Navbar right links -->

						<ul class="navbar-nav ml-auto flex-row align-items-center">
								
								<li class="nav-item">
									 <carrito-compra></carrito-compra>
								</li>

								<li class="nav-item">
									 <notificaciones></notificaciones>
								</li>
								
								<li class="nav-item">
									<header-user></header-user>
								</li>
					
								<div class="conten-action-user d-flex justify-content-between align-items-center">
										
										<el-popover trigger="click"
											placement="bottom" width="320">
											<div class="container-fluid">
												<div class="row">
													<div class="col-12">
														<Label>Puedes agregar tantas observaciones como desees.</Label>
														<small><strong>Nota</strong></small>
														<br>
														<small>Las Observaciones se registran considerando el modulo en donde te encuentras. <br>Encargate de que tu Observación este pensado en que mejoremos el mismo. </small><br>
														<small>
															SI tu observación no tiene nada que ver con este modulo, lo desacheremos.
															Atenderemos las observaciones a la brevedad.
														</small>

														<div class="form-group">
															<label for="comentario">Observación <span class="required">*</span></label>

															<div class="input-group input-group-sm">
																<el-input type="textarea" v-model="observacion.comentario" id="comentario" :rows="3" class="w-100" placeholder="Ingrese su Observación" :class="{'is-invalid' : errors.comentario}"></el-input>
															
																<div class="invalid-feedback" role="alert" v-if="errors.comentario">
																	<strong v-text="errors.comentario[0]"></strong>
																</div>

															</div>
														
														</div>


														<div class="form-group">
															<label for="adjunto">Adjunto un archivo o un capture (recomendado) | opcional</label>

															<div class="input-group input-group-sm">
																<b-form-file v-model="observacion.adjunto" ref="imagen"  :state="Boolean(observacion.adjunto)" placeholder="Archivo..."/>
															
																<div class="invalid-feedback" role="alert" v-if="errors.adjunto">
																	<strong v-text="errors.adjunto[0]"></strong>
																</div>
																
															</div>
														
														</div>

													
													</div>
												</div>

												<div class="row">
													<div class="col-12">
														<el-button type="primary" size="small" @click="enviarObservacion" icon="fas fa-save mr-2" :disabled="observacion.comentario.length <= 15">Enviar</el-button>
													</div>
												</div>


											</div>
											<el-button type="primary" slot="reference" circle size="small" class="bg-transparent py-0"><i class="fas fa-comment-dots text-primary" style="font-size:15pt"></i></el-button>
										</el-popover>
                         </div>
                         <!-- Chat para usuarios -->
                      	<!-- <li class="nav-item">
									<chat-user></chat-user>
								</li> -->

						</ul>
</template>

<script>

import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';

import ChatUser from '../chat.vue';
import CarritoCompra from '../CarritoCompra.vue';


export default{
		
		components:{
			ChatUser,
			CarritoCompra,
		},

		data(){
			return {

				showUsuario:false,
				AppName:'JiitsiMeet',
				observacion:{
						id        :null,
						modulo    :'',
						comentario:'',
						adjunto   :null,
						usuario_id:null,
						usuario   :null,
						atendido  :false
				}

			}
		},

		computed:{
			...mapState(['loading','errors']),
			...mapState('usuario',['usuario']),
			...mapGetters('observaciones',['draft']),

		},

		mounted(){
			this.observacion.comentario = this.draft.comentario;
		},

		methods:{

			...mapActions('observaciones',['guardarObservacion']),



			enviarObservacion(){
				
				this.observacion.modulo = window.location.pathname;


				let formData = new FormData();
				
				formData.append('modulo', window.location.pathname);
				formData.append('comentario', this.observacion.comentario);
				formData.append('adjunto', this.observacion.adjunto);
				formData.append('usuario_id', this.usuario.id);

				this.guardarObservacion(formData).then(respon => {
					
					if(respon.data.success){
						this.observacion.comentario = '';
						this.observacion.adjunto = null;
						this.$notify.success('Tu observación se ha enviado exitosamente')
					}else{
						this.$notify.info('Tu observación no se pudo enviar, inténtelo de nuevo mas tarde')
					}

				}).catch(e => {
					console.log(e);
				});

			}
		
		},


		watch:{
			
			draft(){
				this.observacion.comentario = this.draft.comentario;
			}

		}


	}
</script>


<style lang="scss" scope>
	.text-popover{
		overflow-wrap:normal;
		text-align:center;
		line-height: 15pt;
		font-size:12pt;
	}

	small,label{
		word-break: normal;
	}

</style>