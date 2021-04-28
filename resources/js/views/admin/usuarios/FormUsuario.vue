<template>
	
	<el-dialog :title="title" :visible="showDialog" :before-close="close" :fullscreen="false" :modal="false" width="80%" top="5vh" close-on-press-escape>
			<form autocomplete="off" @submit.prevent="submit">
				<div class="container-fluid">
					<div class="row">
						
						<div class="col-12 col-md-6">
						
								<div class="form-group" :class="{'has-danger': errors.nombre}">
										<label for="nombre">Nombre | <span class="required">*</span> </label>
										<div class="input-group">
											<input type="text" v-model="formulario.nombre" id="nombre" :class="{'is-invalid' : errors.nombre }" class="form-control form-control-sm" placeholder="Nombre">

											<div class="invalid-feedback d-flex" role="alert" v-if="errors.nombre">
												<strong v-text="errors.nombre[0]"></strong>
											</div>


										</div>
								</div>

								<div class="form-group" :class="{'has-danger': errors.nombre}">
										<label for="apellido">Apellido | <span class="required">*</span> </label>
										<div class="input-group">
											<input type="text" v-model="formulario.apellido" id="apellido" :class="{'is-invalid' : errors.apellido }" class="form-control form-control-sm" placeholder="Apellido">

											<div class="invalid-feedback d-flex" role="alert" v-if="errors.apellido">
												<strong v-text="errors.apellido[0]"></strong>
											</div>


										</div>
								</div>

								<div class="form-group" :class="{'has-danger' : errors.telefono}">
											<label for="telefono">Teléfono | <span class="required">*</span></label>
											<div class="input-group">
												
												 <the-mask type="tel" placeholder="Phone: +584128505504"  title="Es necesario el código de pais telefónico: +000 y despues el resto del número, si su Pais tiene dos digitos, como código, inicie siempre con 0 para completar" data-toggle="tooltip" class="form-control form-control-sm" :class="{'is-invalid' : errors.telefono}" v-model="formulario.telefono" :mask="masks" :masked="masked" :hexTokens="regEspecial" />
											

												<div class="invalid-feedback d-flex" role="alert" v-if="errors.telefono">
													<strong v-text="errors.telefono[0]"></strong>
												</div>

											</div>

								</div>



						</div>

						<div class="col-12 col-md-6">

								<div class="form-group" :class="{'has-danger': errors.rol}">
											<label for="rol">Rol | <span class="required">*</span> </label>
											<div class="input-group">
												<el-select v-model="formulario.rol_id" filterable placeholder="Seleccione" size="small"  class="w-100">
													<el-option v-for="rol in roles" :key="rol.id" :value="rol.id" :label="rol.name"></el-option>
												</el-select>

												<div class="invalid-feedback d-flex" role="alert" v-if="errors.rol">
													<strong v-text="errors.rol[0]"></strong>
												</div>

											</div>
								</div>

						
								<div class="form-group" :class="{'has-danger': errors.email}">
										<label for="email">Email | <span class="required">*</span> </label>
										<div class="input-group">
											<input type="email" v-model="formulario.email" id="email" :class="{'is-invalid' : errors.email }" class="form-control form-control-sm" placeholder="email">

											<div class="invalid-feedback d-flex" role="alert" v-if="errors.email">
												<strong v-text="errors.email[0]"></strong>
											</div>

										</div>
								</div>

								<div class="form-group" :class="{'has-danger': errors.direccion}">
										<label for="direccion">Dirección</label>
										<div class="input-group">
											
											<textarea v-model="formulario.direccion" class="form-control form-control-sm" :class="{'in-invalid' : errors.direccion}" placeholder="Dirección..."></textarea>

											<div class="invalid-feedback d-flex" role="alert" v-if="errors.direccion">
												<strong v-text="errors.direccion[0]"></strong>
											</div>

										</div>
								</div>


						</div>

					</div>
					
					<div class="row mt-3">
						<div class="col-12">
							<el-button-group>
								<el-button type="primary" native-type="submit" :loading="loading" size="small"><i class="fas fa-save mx-3"></i>Guardar</el-button>
								<el-button type="info" native-type="button" v-if="formulario.id" @click="nuevoUsuario" size="small">Nuevo Usuario</el-button>
							</el-button-group>
							 
						</div>
					</div>
				</div>
			</form>
	</el-dialog>

</template>

<script>
	
	import { mapState, mapGetters,mapMutations, mapActions } from 'vuex';

	export default{  
			
			props:{
				showDialog:{
					type:Boolean,
					required:true,
				},
				
				title:{
					type:String,
					default:'Crear Usuario',
				},
	
			},

			data(){
				return {
					titleDialog:'',
					formulario:{
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
				
					PickerOptions:{
						disabledDate(time){
							return time.getTime() > Date.now()
						}
					},

					regEspecial:{
                  F:{
                     pattern:/^(\(\+?\d{2,3}\)[\*|\s|\-|\.]?(([\d][\*|\s|\-|\.]?){6})(([\d][\s|\-|\.]?){2})?|(\+?[\d][\s|\-|\.]?){8}(([\d][\s|\-|\.]?){2}(([\d][\s|\-|\.]?){2})?)?)$/,
                  }
               },

               regEspecialClabe:{
               	Z:{
               		pattern:/[0-9]{18}/
               	}
               },

					masked:true,
					mask:'+##(###) ###-##-##',
				}
			},

			
			computed:{
				...mapState(['errors','loading']),
				...mapState('rol',['roles']),
				...mapGetters('usuario',['draft']),

				masks(){
						return this.mask.split('|');
					},

			},

			methods:{

				...mapMutations('usuario',['pushUsuario','clearUsuario','capturarUsuario','updateUsuario']),
				...mapActions('usuario',['guardar']),


				nuevoUsuario(){
					this.clearUsuario();
				},

				close(){
					this.nuevoUsuario();
					this.$emit('cerrarModal');
            
            },

        
            submit(){

	         		this.$store.commit('toggleLoading');

	         		this.guardar(this.formulario).then(respon => {

	         			if(respon.data.success){
	         				

	         				if(this.draft.id){
	         					
	         					this.$notify({
	              					title:'Actualización exitosa',
	              					type:'success',
	              					duration:4000
	              				});
	              				
	              				this.$emit('recargar-usuario');
	              				this.updateUsuario(respon.data.usuario);

	         				}else{
	         					this.$notify({
	              					title:'Registro exitoso',
	              					type:'success',
	              					duration:4000
	              				});
	              				this.pushUsuario(respon.data.usuario);
	              				this.capturarUsuario(respon.data.usuario.id);
	         				}
	         				

	         				this.$store.commit('clearErrors');


	         			}else{
									this.$notify({
	              					title:'Error en el servidor, inténtelo mas tarde',
	              					type:'info',
	              					duration:4000
	              				});
	         			}

	         		}).catch(e => {
	         			console.log(e);

	         			if(e.response.status === 422){
	         				this.$store.commit('setError',respon.data.errors)
	         			}else{
	         				console.log(e);
	         			}
	         		}).then(() => {
	         			this.$store.commit('toggleLoading');
	         		})

              },

			},


			watch:{
				draft(){
					this.formulario = this.draft;
				}
			}

	} 

</script>