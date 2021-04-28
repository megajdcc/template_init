<template>
	
	<el-dialog :title="title" :visible="showDialog" :before-close="close" @open='initForm' :fullscreen="false" :modal="false" width="40%" top="5vh" :close-on-click-modal="true">
			<form autocomplete="off" @submit.prevent="submit">
				<div class="container-fluid">
					<div class="row">
						
						<div class="col-12">
						
								<div class="form-group" :class="{'has-danger': errors.name}">
										<label for="nombre">Nombre Rol | <span class="required">*</span> </label>
										<div class="input-group">
											<input type="text" v-model="formulario.name" id="nombre" :class="{'is-invalid' : errors.name }" class="form-control form-control-sm" placeholder="Rol">

											<div class="invalid-feedback d-flex" role="alert" v-if="errors.name">
												<strong v-text="errors.name[0]"></strong>
											</div>


										</div>
								</div>

							

						</div>



					</div>

					<div class="row">
							<div class="form-group" :class="{'has-danger': errors.nombre}">
										<label for="nombre">Permisos | <span class="required">*</span> </label>
										
											
											<el-checkbox-group v-model="formulario.permisos">
												<el-checkbox v-for="permiso in permisos" :label="permiso.id" :key="permiso.id" >{{ permiso.name }}</el-checkbox>
											</el-checkbox-group>
											
								</div>

					</div>
					<div class="row">
						<div class="col-12">
							 <el-button type="primary" native-type="submit" :loading="loading" size="small"><i class="fas fa-save mx-3"></i>Guardar</el-button>
							 <el-button type="info" native-type="button" v-if="formulario.id" @click="nuevoRol" size="small">Nuevo Rol</el-button>
						</div>
					</div>
				</div>
			</form>
	</el-dialog>

</template>

<script>
	import { mapState, mapGetters, mapMutations, mapActions} from 'vuex'; 
	
	export default{  
		
			props:{
				showDialog:{
					type:Boolean,
					required:true,
				},

			},

			data(){
				return {

					formulario:{
						nombre:null,
						id    :null,
						panel :null,
						permisos:[],
					},
					
					PickerOptions:{
						disabledDate(time){
							return time.getTime() > Date.now()
						}
					},

			

				}
			},

			computed:{
				...mapState(['loading','errors']),
				...mapGetters('rol',['draft']),
				...mapState('permiso',['permisos']),

				title(){
					return (this.formulario.id) ? 'Editar Rol' : 'Crar Rol';
				}

			},

			methods:{
				...mapMutations('rol',['clearRol','pushRol','setRol']),
				...mapActions('rol',['guardarRol']),

				cargarPermisos(){
					this.permisos = this.permissions(this.formulario.panel_id);
				},

				nuevoRol(){
					this.clearRol();
					this.permisos = this.permissions(null);
				},

				initForm(){	
					
					this.formulario = this.draft
					
				},

				close(){
               this.$emit('cerrar');
            },
        
            submit(){

	              	this.$store.commit('toggleLoading');

	              	this.guardarRol(this.formulario).then(respon =>{

	              			if(respon.data.success){

	              				this.$notify({
	              					title:respon.data.message,
	              					type:'success',
	              					duration:4000
	              				})

	              				this.pushRol(respon.data.rol);
	              				this.setRol(respon.data.rol);
	              				

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

	              			console.log(e);

	              			if(e.response.status === 422){
	              				this.$store.commit('setError',e.response.data.errors)
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
					this.formulario = this.draft
				}
			}

	} 

</script>