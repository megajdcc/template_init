<template>
	
	<el-dialog :title="title" :visible="showDialog" :before-close="close" @open='initForm' :fullscreen="false" :modal="false" width="50%" top="5vh" close-on-press-escape>
			<form autocomplete="off" @submit.prevent="submit">
				<div class="container-fluid">
					<div class="row">
						
						<div class="col-12">
						
								<div class="form-group" :class="{'has-danger': errors.nombre}">
										<label for="nombre">Nombre del permiso | <span class="required">*</span> </label>
										<div class="input-group">
											
											<input type="text" v-model="formulario.name" id="nombre" :class="{'is-invalid' : errors.name }" class="form-control form-control-sm" placeholder="Nombre del permiso">

											<div class="invalid-feedback d-flex" role="alert" v-if="errors.name">
												<strong v-text="errors.name[0]"></strong>
											</div>

										</div>
								</div>
					

						</div>

					


					</div>

					<div class="row">
						<div class="col-12">
							 <el-button type="primary" native-type="submit" :loading="loading"><i class="fas fa-save mx-3"></i>Guardar</el-button>
							 <el-button type="info" native-type="button" v-if="formulario.id" @click="nuevoPermiso">Nuevo Permiso</el-button>
						</div>
					</div>
				</div>
			</form>
	</el-dialog>

</template>

<script>
	

	import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';

	export default{  
		
			props:{
				showDialog:{
					type:Boolean,
					required:true,
				},
			},

			data(){
				return {


					formulario:{},
				
					PickerOptions:{
						disabledDate(time){
							return time.getTime() > Date.now()
						}
					},

				}
			},

			computed:{
				...mapState(['errors','loading']),
				...mapGetters('permiso',['draft']),


				title(){

					return (this.formulario.id) ? 'Editar Permiso' : 'Crear Permiso';
				}
			
			},


			methods:{

				...mapActions('permiso',['guardarPermiso']),
				...mapMutations('permiso',['clearPermiso','pushPermiso','setPermiso']),

				initForm(){		
					this.formulario = this.draft;		
				},

				nuevoPermiso(){
				
					this.clearPermiso()
				},



				close(){
					
               this.$emit('cerrar');
                
            },

        
            submit(){

	              this.$store.commit('toggleLoading');


	              this.guardarPermiso(this.formulario).then(respon => {

	              			if(respon.data.success){

	              				this.$notify({
	              					title:respon.data.message,
	              					type:'success',
	              					duration:4000
	              				})

	              				this.pushPermiso(respon.data.permiso);
	              				this.setPermiso(respon.data.permiso);

	              			}else{
	              				this.$notify({
	              					title:respon.data.message,
	              					message:'Inténtelo de nuevo mas tarde',
	              					type:'info',
	              					duration:4000
	              				})
	              			}

	              			this.$store.commit('clearErrors');

	              		}).catch(e => {

	              			if(e.response.status === 422){

	              				this.$store.commit('setError',e.response.data.errors);
	              			}else{

	              				console.log(e)
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