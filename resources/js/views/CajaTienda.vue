<template>
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<h2>Envío</h2>
			</div>
		</div>

		<div class="row">
			<div class="col-12">

				<el-steps :active="active" finish-status="success">
					<el-step title="Opciones de envío">
						
						
					</el-step>

					<el-step title="Revisa">
						
					</el-step>

				</el-steps>
	


				<template v-if="active == 1">

					<div class="form-group d-flex flex-column">
							<label class="mt-3">Elija la opción de envío que desee:</label>
							<el-radio-group  v-model="formulario.opcion_eleccion">
								<el-radio :label="1">Email</el-radio>
								<el-radio :label="2">Descargar</el-radio>
							</el-radio-group>
					</div>
					

					<template v-if="formulario.opcion_eleccion == 1">
						
							<div class="container">
								<div class="row">
									<div class="col-12 col-md-6">
										<div class="form-group">
											<label>Confirme su correo electrónico</label>
											<div class="input-group">
												<input type="email" v-model="formulario.email" class="form-control form-control-sm w-md-50" placeholder="example: example@domain.com" :class="{'is-invalid' : errors.email}">
												<div class="invalid-feedback d-flex" role="alert" v-if="errors.email">
														<strong v-text="errors.email[0]"></strong>
												</div>
											</div>
										</div>

										<div class="form-group">
											<label>Vuelva a escribir el correo electrónico</label>
											<div class="input-group">
												<input type="email" v-model="email_confirmar" class="form-control form-control-sm w-md-50" placeholder="example: example@domain.com" :class="{'is-invalid' : errors.email}">
												<div class="invalid-feedback d-flex" role="alert" v-if="errors.email">
														<strong v-text="errors.email[0]"></strong>
												</div>
											</div>
										</div>

									</div>
								</div>
							</div>
							
						</template>	

						<el-button-group>
							<el-button type="primary" native-type="button" :loading="loading" @click="irRevisar" :disabled="checkEmail">Próximo paso</el-button>
						</el-button-group>
				
					
				</template>


				<template v-else-if="active == 2">
					<revision-shopping></revision-shopping>
				</template>
			</div>
		</div>
	</div>
</template>

<script>
	import {TheMask} from 'vue-the-mask';
	import RevisionShopping from './RevisionShopping.vue';

	import {mapState,mapGetters,mapMutations,mapActions} from 'vuex';

	export default{

		components:{
			RevisionShopping
		},
		
		data(){
			return{
					active:1,
					errors:{},
					email_confirmar:'',
					formulario:{
						email:null,
						telefono:null,
						opcion_eleccion:1,

					},

					mask:'+#############',
               masked:true,
               regEspecial:{
                  F:{
                     pattern:/^(\(\+?\d{2,3}\)[\*|\s|\-|\.]?(([\d][\*|\s|\-|\.]?){6})(([\d][\s|\-|\.]?){2})?|(\+?[\d][\s|\-|\.]?){8}(([\d][\s|\-|\.]?){2}(([\d][\s|\-|\.]?){2})?)?)$/,
                  }
               },




			}
		},

		computed:{
			...mapState(['loading','errors']),
			...mapGetters('usuario',['getTotalCarrito','getFotoInCarrito']),
			...mapState('usuario',['usuario']),


            masks(){
               return this.mask.split('|');
            },


            checkEmail(){

            	var result = false;

            	if(this.formulario.opcion_eleccion == 1){
            			if(this.formulario.email == this.email_confirmar){
            				result = false;
            			} else{
            				result = true;
            			}

            	}

            	return result;

            }

		},

		created(){

			this.$eventHub.$on('regresarPosition', () => {
				this.formulario.email = null
				this.active = 1
			})


			this.$eventHub.$on('pagoCompletado', (response) => {
				this.confirmarCompra(response)
			})

		},

		methods:{
			...mapActions('venta',['generar']),
			...mapMutations('usuario',['updateCarrito']),

			irRevisar(){
				
				if(this.formulario.opcion_eleccion == 3){
					
					this.formulario = {
						opcion_eleccion: this.formulario.opcion_eleccion
					}

				}




				axios.post('/tienda/ir/revisar',this.formulario).then(respon => {
						
						if(respon.data.success){
							this.active++;
						}

						

				}).catch(e => {
					if(e.response.status === 422){
						this.errors = e.response.data.errors
					}else{
						console.log(e)
					}

				}).then(() => {
					
				})

			},


			confirmarCompra(response){


				
				if(response.state == 'approved'){
					this.$store.commit('toggleLoading');

					let data = {
						monto          :this.getTotalCarrito,
						fotos          :this.getFotoInCarrito,
						pagado         :true,
						respon_paypal  :response,
						condicion_envio:this.formulario.opcion_eleccion,
						email_envio    :this.formulario.email,
						comprador_id   :this.usuario.id

					};

					this.generar(data).then(respon => {

						if(respon.data.success){


							if(this.formulario.opcion_eleccion == 2){
								window.open(respon.data.venta.url_zip,'Mi Compra');
							}

							Swal.fire({
									title:'Compra realizada exitosamente',
									text:respon.data.mensaje,
									icon:'success',
									showCancelButton:false,
									allowOutsideClick:false,
									allowEscapeKey:false,
									confirmButtonText:'OK!',
								}).then((result) => {
									if(result.value){	
										this.active = 1;
										this.formulario = {
																	email:null,
																	telefono:null,
																	opcion_eleccion:1,
																};
																
										 this.updateCarrito(respon.data.carrito);
										 this.$router.push({name:'tienda'});
									}

							})


						}


					}).catch(e => {
						console.log(e)
					}).then(() => {
						this.$store.commit('toggleLoading');
					});


				}


			



			}


		}


	}

</script>