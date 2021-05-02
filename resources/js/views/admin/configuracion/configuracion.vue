<template>
	
	<div class="container">
		<div class="row">
			<div class="col-12">

				<div class="card">
					<div class="card-header py-1 my-0 d-flex align-items-center text-white justify-content-between" style="background-color: #444444">
						<h3 class="font-weight-bold my-auto">{{ titulo }}</h3>
					
					</div>


					<div class="card-body container-fluid">
						<div class="row">
			
							<div class="col-12 col-md-6">
								
								<div class="form-group">
									<label for="api_key">PayPal Cliente Id</label>
									<div class="input-group">
										<input type="text" v-model="formulario.paypal_id" class="form-control" :class="{'is-invalid' : errors.paypal_id}" placeholder="Ingrese el Paypal Client ID ">
									</div>
								</div>

								<div class="form-group">
									<label for="api_key">PayPal Secret Id</label>
									<div class="input-group">
										<input type="text" v-model="formulario.paypal_secret" class="form-control" :class="{'is-invalid' : errors.paypal_secret}" placeholder="Ingrese el Paypal Secret ID ">
									</div>
								</div>


								<div class="form-group">
									<label for="api_key">Producción o prueba</label>
									<div class="input-group">
										
										<el-radio-group v-model="formulario.production_paypal">
											<el-radio :label="true">Producción</el-radio>
											<el-radio :label="false">En prueba</el-radio>							
										</el-radio-group>
										<strong class="text-danger " v-if="errors.production_paypal" v-text="errors.production_paypal[0]"></strong>
									</div>
								</div>


							</div>


							<div class="col-12 col-md-6">
								<div class="form-group">
									<label for="">Imagen de marca de agua</label>
									<div class="input-group">

										<el-upload 
											:action="urlUploadFoto" 
											list-type="picture-card"  
											:on-preview="verFoto" 
											:headers="cabeceraUploadFotos" 
											:data="formulario" 
											class="d-flex flex-wrap w-100 justify-content-center"
											name="foto" 
											accept="image/jpg, image/png, image/jpeg" drag 
											:on-success="fotoCargada" 
											show-file-list 
											:file-list="fotos">
													<template v-slot:default>
														<div class="content-input-image">
															<span class="el-icon-plus"></span>
															<!-- <p>Arrastra o carga la foto</p> -->
														</div>
													</template>

											</el-upload>

									</div>
								</div>
							</div>


						</div>
						
					</div>


						<div class="card-footer">
									<el-button-group>
										<el-button type="primary" :loading="loading" size="small" icon="el-icon-s-promotion mr-2" @click="guardar">Guardar</el-button>
									</el-button-group>
						</div>

				</div>
				
			</div>
		</div>

		<el-dialog :visible.sync="showDialog" top="5vh" :modal="false">
			<img width="100%" :src="dialogoImageUrl" alt="">
		</el-dialog>
		
	</div>



</template>

<script>

import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';

export default {
  data () {
    return {
    	titulo:'PayPal y Marca de Agua',
    	showDialog:false,
    	dialogoImageUrl:'',
    	formulario:{
    		id:null,
    		paypal_id:'',
    		paypal_secret:'',
    		production_paypal:false,
    		marca_agua:'',

    	},
    	
    	cabeceraUploadFotos:{
			'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content'),
		},

    }
  },


  computed:{
  	...mapState(['errors','loading']),
  	...mapGetters('configuracion',['draft']),

  		urlUploadFoto(){
			return (this.formulario.id) ? `/configuracion/configuracions/${this.formulario.id}/upload/marca-agua` : ``;
		},

		fotos(){

			if(this.formulario.marca_agua){
					return [{
					url:this.formulario.marca_agua,
					alt:'Marca de Agua'
				}];
			}

			return [];
		

		}


  },

  mounted(){
  	this.formulario = this.draft;
  },

  methods:{
	  	...mapActions('configuracion',['guardarConfiguracion']),
	  	...mapMutations('configuracion',['setConfiguracion']),


	  	guardar(){

	  		this.$store.commit('toggleLoading');

	  		this.guardarConfiguracion(this.formulario).then(respon => {

  					if(respon.data.success){
  					
  						this.$notify.success('Se ha guardado con éxito');
  						this.setConfiguracion(respon.data.configuracion);
  					
  					}else{
  					
  						this.$notify.info('No se pudo guardar, inténtelo de nuevo mas tarde');
  					}

  					this.$store.commit('clearErrors');

	  		}).catch(e => {
	  			
	  			if(e.response.status === 422){
	  				this.$store.commit('setError',e.response.data.errors);
	  			}else{
	  				console.log(e);
	  			}

	  		}).then(() => {
	  		
	  			this.$store.commit('toggleLoading');
	  		
	  		})

	  	},



		verFoto(file){
		
			this.dialogoImageUrl = file.url;
			
			this.showDialog = true;
		},

		fotoCargada(response,file){
			this.setConfiguracion(response.configuracion);
			// this.formulario.marca_agua = response.marca;
		},



  	},


  watch:{
  	
  	draft(){
  		this.formulario = this.draft;
  	}

  }

}
</script>

<style lang="scss">
.content-tipo{
	height:150px;
	width:160px;
	background: white;
	display:flex;
	border-radius: 10px;
	
	img{
		width:160px;
	}

}

.content-tipo:hover{
	box-shadow: 0px 20px 25px 4px #00000096;
	cursor:pointer;
}

.el-upload-dragger{

	width:100%;
	display:flex;
	justify-content: center;
	align-items:center;
	width:100% !important;
	height:100%;

}

.content-input-image{
	width:100%;
	height:100%;
	border:none;
	border-radius:10px;
	display:flex;
	justify-content:center;
	flex-direction:column;
	align-items: center;
	
	p{
		width:150px;
	}

}

</style>