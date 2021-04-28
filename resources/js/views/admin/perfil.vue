<template>
	
	<div class="container-fluid" v-if="usuario.id">
		<div class="row">
			<div class="col-12">
				<el-tabs type="border-card">
					
					<el-tab-pane label="Perfil" stretch="true" lazy>
						
						<div class="container-fluid">
							<div class="row">
								
								<div class="col-12 col-md-4 d-flex flex-column align-items-center">

									<div class="mifile_pond" >
											<FilePond 
											max-files="1" 
											accepted-file-types="image/jpeg, image/png, image/gif"
											:files="usuario.avatar"
											:label-idle="label_cargue_img"
											ref="pond"
											:server="upload_avatar"
											instant-upload="false"
											allowFilePoster="true"
											allowImagePreview="true"
											filePosterMaxHeight="270px"
											imagePreviewMarkupShow="false"
											/>
									</div>
									

									<label class="required text-center"> Solo se permiten imágenes del tipo (jpeg, png y gif) y que no sean mayores a 4MB</label>
								</div>
								
								<div class="col-12 col-md-8">
									<h3 class="text--center">{{ usuario.nombre +' '+usuario.apellido }}</h3>

										<p class="text--center text-sm-left mb-4 d-flex flex-wrap">{{ usuario.email+' ' }} <strong class="ml-2" v-show="usuario"> | {{ usuario.rol.name  }} </strong></p>

										<div class="row mb-4">
											<div class="col-6 col-lg-4">
												<p class="h5 mb-1">Direcci&oacute;n:</p>
												<address>
													<span class="d-block text-secondary">{{ usuario.direccion }}</span>
												</address>
											</div>
											<div class="col-6 col-lg-4">
												<p class="h5 mb-1">Tel&eacute;fono:</p>
												<address>
													<the-mask type="tel" readonly class="form-control form-control-sm" v-model="telefono" :mask="masks" :masked="masked" v-show="false"></the-mask>
													<p class="text--left text-secondary">{{ usuario.telefono }}</p>
												</address>
											</div>

											<div class="col-6 col-lg-4">
												<p class="h5 mb-1">Fecha de nacimiento:</p>
												<address>
													<p class="text--left text-secondary">{{ getFechanacimiento }}</p>
												</address>
											</div>
										</div>
									
										<div class="row">
												<div class="" title="Modificar mis datos" data-toggle="tooltip">
													<button type="button" class="btn btn-outline-primary"  @click="showDialogForm = true"><i class="fa fa-edit mr-2"></i>Editar</button>
												</div>
										</div>
								</div>
							</div>
						</div>

					</el-tab-pane>
					
					<el-tab-pane label="Seguridad">
							<h3 class=""> Cambiar Contraseña:</h3>
						
							<form autocomplete="off" @submit.prevent="changePassword">
							
								<div class="form-group" :class="{'has-danger': errors.pass_actual}">
									<label for="pass-actual">Contraseña</label>
									<div class="input-group">
										<input type="password" id="pass-actual" v-model="data.pass_actual" :class="{'is-invalid' : errors.pass_actual }" class="form-control" placeholder="Contraseña actual">
									 
									 <div class="invalid-feedback d-flex" role="alert" v-if="errors.pass_actual">
                           	 <strong v-text="errors.pass_actual[0]"></strong>
                        	</div>
									</div>
								</div>


								<div class="form-group" :class="{'has-danger': errors.pass_new}">
									<label for="pass-new">Contraseña nueva</label>
									<div class="input-group">
										<input type="password" v-model="data.pass_new" id="pass-new" placeholder="Contraseña nueva" :class="{'is-invalid' : errors.pass_new }" class="form-control">
										
										<div class="invalid-feedback d-flex" role="alert" v-if="errors.pass_new">
                           	 <strong v-text="errors.pass_new[0]"></strong>
                        		</div>
									</div>
								</div>

								<div class="form-group" :class="{'has-danger': errors.pass_confirm}">
									<label for="pass-confirm">Confirmar Contraseña </label>
									<div class="input-group">
										<input type="password" v-model="data.pass_confirm" id="pass-confirm" :class="{'is-invalid' : errors.pass_confirm }" placeholder="Confirmar contraseña" class="form-control">
										<div class="invalid-feedback d-flex" role="alert" v-if="errors.pass_confirm">
                           	 <strong v-text="errors.pass_confirm[0]"></strong>
                        		</div>
									</div>
								</div>
								

								<div class="alert alert-success d-none" id="mensaje_div">
									<span id="respuesta_mensaje"></span>
								</div>

								 <el-button type="primary" native-type="submit" :loading="loading"><i class="fas fa-save mx-3"></i>Guardar</el-button>
							</form>

					</el-tab-pane>
			
				</el-tabs> 
			</div>
			
		</div>
		<form-perfil :showDialog.sync="showDialogForm" @cerrar="showDialogForm = false"></form-perfil>
	</div>
</template>


<script>
	
import vueFilePond from 'vue-filepond';
import 'filepond/dist/filepond.min.css';

import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';

import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginFilePoster from 'filepond-plugin-file-poster';

import 'filepond-plugin-file-poster/dist/filepond-plugin-file-poster.css';

import FormPerfil from './FormPerfil.vue';

import { mapState, mapActions, mapMutations} from 'vuex';

	export default{
			components:{
				FilePond: vueFilePond(FilePondPluginImagePreview, FilePondPluginFileValidateType, FilePondPluginFilePoster),
				FormPerfil,
			},
			data(){
				return{
					showDialogForm:false,
					masked:true,
					mask:'+##(###) ###-##-##',

					upload_avatar:{
						url:'/upload/avatar',
						process:{

							headers:{
								'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content'),
							},
							
							onload:(response) => {
								var json = JSON.parse(response);
								this.updateAvatar(json);
							
							},

							onerror:res =>{
									console.log('error');
							}
						}
					},

					label_cargue_img:"Actualice su avatar",
					data:{ pass_actual: '', pass_confirm:'',pass_new:''},


				}
			},


			computed:{	
				...mapState('usuario',['usuario']),
				...mapState(['loading','errors']),

				getFechanacimiento(){
						return moment(this.usuario.fecha_nacimiento).format('DD-MM-YYYY');
				},
				
				masks(){
						return this.mask.split('|');
				},

				telefono:{
					
					get(){
						return this.usuario.telefono
					},

					set(val){
						this.setTelefono(val)
					}
				},


				

			},

			methods:{
				...mapActions('usuario',['cargarUsuario']),
				...mapMutations('usuario',['setTelefono','updateAvatar']),



				async changePassword(){
					

					this.$store.commit('toggleLoading');

					await axios.post(`/cambiar/contrasena`, this.data).then(response => {

						if(response.data.success){
							
							this.data = { pass_actual: '', pass_confirm:'',pass_new:''}
							
							this.$store.commit('clearErrors');

							this.$notify({
								title:'Contraseña actualizada',
								type:'success',

							})
						
						}else{
							this.$notify({
								title:'No pudimos actualizar tu contraseña, inténtalo de nuevo mas tarde.',
								type:'info',
							})
						}


					}).catch(e =>{

						if(e.response.status === 422){
								this.$store.commit('setError',e.response.data.errors);
							}else{
								console.log(e);
							}


					}).then(() => {
						this.$store.commit('toggleLoading');
					})
				},
			},





	}

</script>

<style lang="scss" scope>
	.mifile_pond{
		flex:1 1 auto;
		height:300px;
		width:100%; 
	}
</style>