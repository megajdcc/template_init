<template>
	
	<el-dialog :title="titleDialog" :visible="showDialog" :before-close="close" :fullscreen="false" :modal="false" width="70%" top="5vh" >
			<form autocomplete="off" @submit.prevent="submit" v-loading="loading">
				<div class="container-fluid">
					<div class="row">
						
						<div class="col-12 col-md-6">
								<div class="form-group" :class="{'has-danger': errors.nombre}">
										<label for="nombre">Nombre | <span class="required">*</span> </label>
										<div class="input-group">
											<input type="text" v-model="draft.nombre" id="nombre" :class="{'is-invalid' : errors.nombre }" class="form-control form-control-sm" placeholder="Nombre">

											<div class="invalid-feedback d-flex" role="alert" v-if="errors.nombre">
												<strong v-text="errors.nombre[0]"></strong>
											</div>


										</div>
									</div>

									<div class="form-group" :class="{'has-danger': errors.apellido}">
										<label for="apellido">Apellido | <span class="required">*</span> </label>
										<div class="input-group">
											<div class="input-group">
											<input type="text" v-model="draft.apellido" id="apellido" :class="{'is-invalid' : errors.apellido }" class="form-control form-control-sm" placeholder="Apellido">

											<div class="invalid-feedback d-flex" role="alert" v-if="errors.apellido">
												<strong v-text="errors.apellido[0]"></strong>
											</div>


										</div>
										</div>
									</div>


									<div class="form-group" :class="{'has-danger' : errors.telefono}">
											<label for="telefono">Celular</label>
											<div class="input-group">
												
												<the-mask type="tel" placeholder="Número de celular" class="form-control form-control-sm" :class="{'is-invalid' : errors.telefono}" v-model="draft.telefono" :mask="masks" :masked="masked" />
											

												<div class="invalid-feedback d-flex" role="alert" v-if="errors.telefono">
													<strong v-text="errors.telefono[0]"></strong>
												</div>

											</div>

									</div>

									<div class="form-group" :class="{'has-danger' : errors.fecha_nacimiento}">
											
											<label for="fechanacimiento">Fecha de nacimiento</label>

											<div class="input-group date" id="datetimepicker3" data-target-input="nearest">
												
												<el-date-picker type="date" size="small" class="w-100" value-format="yyyy-M-dd" prefix-icon="fas fa-calendar-day" placeholder="Fecha de Nacimiento" v-model="draft.fecha_nacimiento" :picker-options="PickerOptions" :class="{'is-invalid' : errors.fecha_nacimiento}"></el-date-picker>

												<div class="invalid-feedback d-flex" role="alert" v-if="errors.fecha_nacimiento">
													<strong v-text="errors.fecha_nacimiento[0]"></strong>
												</div>

											</div>
										
									</div>
						</div>

						<div class="col-12 col-md-6">

							<div class="form-group" :class="{'has-danger': errors.email}">
										<label for="email">Email | <span class="required">*</span> </label>
										<div class="input-group">
											<input type="email" v-model="draft.email" id="email" :class="{'is-invalid' : errors.email }" class="form-control form-control-sm" placeholder="email">

											<div class="invalid-feedback d-flex" role="alert" v-if="errors.email">
												<strong v-text="errors.email[0]"></strong>
											</div>


										</div>
									</div>

							<div class="form-group" :class="{'has-danger': errors.direccion}">
										<label for="direccion">Dirección</label>
										<div class="input-group">
											
											<textarea v-model="draft.direccion" class="form-control form-control-sm" :class="{'in-invalid' : errors.direccion}" placeholder="Dirección..."></textarea>

											<div class="invalid-feedback d-flex" role="alert" v-if="errors.direccion">
												<strong v-text="errors.direccion[0]"></strong>
											</div>

										</div>
							</div>
							
						</div>


					</div>

					<div class="row">
						<div class="col-12">
							 <el-button type="primary" native-type="submit" :loading="loading"><i class="fas fa-save mx-3"></i>Guardar</el-button>
						</div>
					</div>
				</div>
			</form>
	</el-dialog>

</template>

<script>

	import {mapState, mapActions, mapMutations} from 'vuex';


	export default {  

			props: ['showDialog'],
			data(){
				return {
					
					titleDialog:'Editar Datos de perfil',

					PickerOptions:{
						disabledDate(time){
							return time.getTime() > Date.now()
						}
					},
					masked:true,
					mask:'+##(###) ###-##-##',

					draft:{},
				}
			},

			created(){

				this.draft = clone(this.usuario)

			},

			computed:{

				...mapState(['loading','errors']),
				...mapState('usuario',['usuario']),
				masks(){
					return this.mask.split('|');
				},
			},


			methods:{
				
				...mapActions('usuario',['guardarUsuario','cargarUsuario']),
				...mapMutations('usuario',['update']),

				close(){
					
               this.$emit('cerrar');
                
              },
         

            submit(){
            		this.$store.commit('toggleLoading')

            		this.guardarUsuario(this.draft).then(response => {
			              				
							   if(response.data.success){

							   	this.$notify({
							   		title:'Usuario Actualizado',
							   		type:'success',
							   	})

							   	this.update(response.data.usuario);

							   }else{
							   	this.$notify({
							   		title:'Usuario no se pudo actualizar',
							   		message:'Intente de nuevo mas tarde',
							   		type:'info',

							   	})
							   }

								
								this.$store.commit('clearErrors');

							}).catch(error => {
							        

							   if (error.response.status === 422) {
							   	this.$store.commit('setError',error.response.data.errors);
							   } else {
							   	console.log(error)
							   }
					              	
					      }).then( () => {
					        	this.$store.commit('toggleLoading')
					    	});
	              
            },

			},
	} 

</script>