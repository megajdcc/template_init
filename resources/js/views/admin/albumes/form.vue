<template>
	<div class="container">
		<div class="row">
			<div class="col-12">

				<div class="card">
					<div class="card-header py-1 my-0 d-flex align-items-center text-white justify-content-between" style="background-color: #444444">
						<h3 class="font-weight-bold my-auto">{{ titulo }}</h3>
					
					</div>


					<div class="card-body container-fluid">
						<div class="row" v-if="step == 1">
							<div class="col-12">
								<div class="form-group">
									<label for="">Nombre del álbum <span class="required">*</span></label>

									<div class="input-group">
										<input type="text" v-model="formulario.nombre" placeholder="Nombre del álbum" class="form-control" :class="{'is-invalid' : errors.nombre}">

										<div class="invalid-feedback" v-if="errors.nombre">
											<strong v-text="errors.nombre[0]"></strong>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<template v-if="step == 2">
							<div class="row">
								<div class="col-12">
									<div class="form-group">
										<label for="">Que precio le quieres poner a tus fotos?  <span class="required">*</span></label>
										<div class="input-group">
											<currency-input v-model="precio" currency="USD" locale="US" class="form-control form-control-sm"></currency-input>
											<strong v-if="errors.precio" class="text-danger" v-text="errors.precio[0]"></strong>
										</div>
									</div>
										
								</div>

								<div class="col-12">
									<div class="form-group">
											<label for="fotos">Agregue las fotos que desee al álbum</label>
											<div class="input-group">
											
											<el-upload 
												class="upload-demo w-100 d-flex flex-column align-items-center"
												:action="urlUploadFoto" 
												:on-remove="eliminarFoto"
												:on-preview="verFoto" 
												:headers="cabeceraUploadFotos" 
												:data="{precio:precio}" 
												name="foto" 
												multiple
												drag 

												accept="image/jpg, image/png, image/jpeg" 
												:on-success="fotoCargada" 
												:file-list="getFotos">
													<i class="el-icon-upload"></i>
													<div class="el-upload__text">Suelta las fotos aquí <em>haz clic para cargar</em></div>
													<div class="el-upload__tip" slot="tip">Solo archivos de imágenes como JPG/PNG</div>
												</el-upload>
											</div>

									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-12">
									<el-divider>Fotos del álbum</el-divider>
									
									
									<div class="d-flex flex-wrap" style="max-height:380px; overflow-y: auto">
										<div class="detail-gallery-preview mb-3 mx-2" v-for="(foto, i) in formulario.fotos" :key="foto.id">
												<img class="img-thumbnail img-rounded gallery-img" :src="foto.foto_con_marca"  alt="Imagen" @click="index = i" style="width:100%; height:auto; object-fit:contain">
											<div class="btn-group btn-group-sm" role="group">
												
												<button class="btn btn-outline-primary"  @click="ajustarPrecio(foto)" type="button"  data-toggle="tooltip" title="Ajustar precio"><i class="fa fa-edit"></i></button>

												<button class="btn btn-outline-danger"  @click="eliminarImagen(foto)" type="button"  data-toggle="tooltip" title="Eliminar Foto"><i class="fa fa-trash"></i></button>

											</div>
										</div>

									</div>
									<vue-gallery-slideshow :images="getFotosGallery" :index="index" @close="index = null"></vue-gallery-slideshow>
								</div>
							</div>
						</template>

					</div>


					<div class="card-footer">
						<el-button-group>

							<el-button type="info" size="small" icon="fas fa-arrow-left mr-2" @click="step--" v-if="step > 1">Regresar</el-button>

							<el-button type="primary" :disabled="verificaNameAlbum" @click="guardarAlbum" size="small" icon="fas fa-arrow-right mr-2" v-if="step == 1">Siguiente</el-button>

							<!-- <el-button type="primary"  @click="guardar" size="small" icon="fas fa-save mr-2">Guardar</el-button> -->
						
						</el-button-group>
					</div>

				</div>

			</div>
		</div>
	</div>

</template>

<script>

import {mapState, mapGetters, mapActions, mapMutations } from 'vuex';
import VueCurrencyinput from 'vue-currency-input';
import VueGallerySlideshow from 'vue-gallery-slideshow';

export default {

	components:{
		VueCurrencyinput,
		VueGallerySlideshow
	},

	props:['titulo'],

  data(){
    return {
    	step:1,
    		index:null,
    	formulario:{
				id:null,
				nombre:'',
				usuario_id:null,
				usuario:null,
				fotos:[],
    	},


    	precio:0,

    	cabeceraUploadFotos:{
			'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content'),
		},
    }

  },


  computed:{
  		...mapState(['errors','loading']),
  		...mapGetters('album',['draft']),


  		verificaNameAlbum(){
  			return (this.formulario.nombre.length < 3)
  		},


  		urlUploadFoto(){
			return (this.formulario.id) ? `/albums/${this.formulario.id}/upload/foto` : ``;
		},

		getFotos(){
			return this.formulario.fotos.map((foto) => {
				return {
					'name':foto.nombre,
					'url':foto.nombre					
				};

			});
		},

		getFotosGallery(){
			
			return this.formulario.fotos.map(foto => {
				return {url:foto.foto_con_marca,alt:foto.foto_con_marca};
			});

		}

  },


  mounted(){
  	this.formulario = this.draft;
  },


  methods:{
  	...mapActions('album',['guardar']),
  	...mapMutations('album',['pushAlbum','capturarAlbum','updateAlbum','pushFoto']),

  	regresar(){
  		this.$emit('regresar');
  	},

  	guardarAlbum(){

  		this.$store.commit('toggleLoading');

  		this.guardar(this.formulario).then(respon => {

  			if(!this.formulario.id){
  				if(respon.data.success) {
					this.$notify.success('Álbum guardado exitosamente');
					this.pushAlbum(respon.data.album);
					this.capturarAlbum(respon.data.album.id);
					this.step++;
	  			}else{
	  				this.$notify.info('No se pudo guardar, inténtelo de nuevo.');
	  			}
  			}else{

  				if(respon.data.success) {
					this.$notify.success('Álbum actualizado exitosamente');
					this.updateAlbum(respon.data.album);
					this.step++;
	  			}else{
	  				this.$notify.info('No se pudo actualizar, inténtelo de nuevo.');
	  			}

  			}

  			

  		}).catch(e => {

  			if(e.response.status === 422){
  				this.$store.commit('setError',e.response.data.errors);
  			}else{
  				console.log(e);
  			}

  		}).then(() => {
  			this.$store.commit('toggleLoading');
  		});
  	},

  	eliminarFoto(file){

  	},

  	verFoto(file){

  	},

  	fotoCargada(response,file){
  		this.updateAlbum(response.album);
  		// this.pushFoto(response.imagen);
  	},

  	eliminarImagen(foto){

  	},

  	ajustarPrecio(foto){

  	}

  },

  watch:{
  	
  	draft(){
  		this.formulario = this.draft;

  	}

  }
}
</script>

<style lang="scss" scoped>

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

.detail-gallery-preview {
	width:200px;
	display: flex;
	flex-direction: column;
}

.detail-gallery-preview img{
	min-height: 165px;
	height:165px;
	max-height: 165px;
	cursor:pointer;
}


</style>