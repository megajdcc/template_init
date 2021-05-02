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
							<div class="col-12">
								<el-button type="primary" size="small" @click="agregarMasFotos" icon="el-icon-upload mr-2">Agregar</el-button>
							</div>
						</div>
						<el-divider></el-divider>
						<div class="row">
							<div class="d-flex flex-wrap" style="max-height:380px; overflow-y: auto">

								<div class="detail-gallery-preview mb-3 mx-2" v-for="(foto, i) in album.fotos" :key="foto.id">
									<img class="img-thumbnail img-rounded gallery-img" :src="foto.foto_con_marca"  alt="Imagen" @click="index = i" style="
									width:100%; height:auto; object-fit:contain">
									<small class="text-black-75">Precio: {{ foto.precio | currency }} USD</small>
									<div class="btn-group btn-group-sm" role="group">
										
										<button class="btn btn-outline-primary"  @click="ajustarPrecio(foto)" type="button"  data-toggle="tooltip" title="Ajustar precio"><i class="fa fa-edit"></i></button>

										<button class="btn btn-outline-danger"  @click="eliminarImagen(foto)" type="button"  data-toggle="tooltip" title="Eliminar Foto"><i class="fa fa-trash"></i></button>

									</div>
								</div>

							</div>
						</div>
							
							<vue-gallery-slideshow :images="getFotosGallery" :index="index" @close="index = null"></vue-gallery-slideshow>
					</div>
				</div>
			</div>
		</div>


		<el-dialog title="Cambiar Precio de foto" width="40%"
		:visible="changePrecio" @close="cerrardialogo" :show-close="false">
			<div class="form-group">
				<div class="form-group">
					<label for="">Que precio le quieres poner a tus fotos?  <span class="required">*</span></label>
					<div class="input-group">
						<currency-input v-model="foto.precio" currency="USD" locale="US" class="form-control form-control-sm"></currency-input>
						<strong v-if="errors.precio" class="text-danger" v-text="errors.precio[0]"></strong>
					</div>
				</div>
			</div>
			<el-button type="primary" @click="cambiarPrecio" size="small" icon="fas fa-save mr-2 mt-2" :loading="loading">Guardar</el-button>
		</el-dialog>

		
		<el-dialog title="Cargar mas fotos" width="60%"
		:visible="showDialog" @close="closeDialog" :show-close="false">
				
				<div class="container-fluid">
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
					</div>

					<div class="row">
						<div class="col-12">
							<div class="form-group">

								<label for="fotos">Agregue las fotos que desee al álbum</label>
								<div class="input-group">
								
									<el-upload 
										class="upload-demo w-100 d-flex flex-column align-items-center"
										:action="urlUploadFoto" 
										:headers="cabeceraUploadFotos" 
										:data="{precio:precio}" 
										name="foto" 
										ref="upload"
										multiple
										drag 
										accept="image/jpg, image/png, image/jpeg"
										:auto-upload="false" 
										:on-success="fotoCargada">
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
							<el-button type="primary" size="small" @click="cargarImagenes" icon="el-icon-upload mr-2">Cargar imágenes</el-button>
						</div>
					</div>

				</div>
			
				
		


		</el-dialog>




	</div>
</template>

<script>
import {mapState,mapGetters,mapActions, mapMutations} from 'vuex';
import VueCurrencyinput from 'vue-currency-input';
import VueGallerySlideshow from 'vue-gallery-slideshow';

export default {
	components:{
		VueGallerySlideshow,
		VueCurrencyinput
	},

	props:['id'],
  	
  	data(){
    return{
    	index:null,
    	changePrecio:false,
    	showDialog:false,

    	foto:{
    		id:null,
    		precio:0,
    	},

    	precio:0,

    	
    	cabeceraUploadFotos:{
			'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content'),
		},


    }
  	},

  	mounted(){
  		this.capturarAlbum(this.id);
  	},

  	computed:{
  		...mapState(['loading','errors']),
  		...mapState('album',['album']),

  		titulo(){
  			return (this.album.id) ? `Fotos del Álbum: ${this.album.nombre}` : 'Fotos de Álbum'
  		},

  		getFotosGallery(){
  			return this.album.fotos.map(foto => {
  				return {url:foto.foto_con_marca,alt:foto.nombre};
  			})
  		},


		urlUploadFoto(){
			return (this.album.id) ? `/albums/${this.album.id}/upload/foto` : ``;
		},



  },

  methods:{
  		...mapMutations('album',['capturarAlbum','updateFotoAlbum','updateAlbum']),
  		...mapActions('album',['eliminarFotoAlbum','cambiarPrecioFoto']),
  		
  		eliminarImagen(foto){
  			this.eliminarFotoAlbum(foto.id);
  		},

  		ajustarPrecio(foto){

  			let fot = clone(foto);

  			this.foto.id = fot.id;
  			this.foto.precio = Number(fot.precio);

  			this.changePrecio = true;
  		},

  		cambiarPrecio(){

  			this.$store.commit('toggleLoading');

  			this.cambiarPrecioFoto(this.foto).then(respon => {
  				
  				if(respon.data.success){
  					
  					this.$notify.success('Se ha actualizado con éxito el precio de la foto');
  					this.updateFotoAlbum(respon.data.foto);
  					
  					this.foto =  {
							    		id:null,
							    		precio:0,
							    	};

					this.changePrecio = false;

  				}else{
  					this.$notify.info('No se pudo actualizar el precio, inténtalo de nuevo');
  				}

  				this.$store.commit('clearErrors');

  			}).catch(e => {
  				
  				console.log(e);
  				if(e.response.status === 422){
  					this.$store.commit('setError',e.response.data.errors);
  				}else{
  					console.log(e);
  				}

  			}).then(() => {
  				this.$store.commit('toggleLoading');
  			});

  		},

  		cerrardialogo(){
  			
  			this.foto = {
	    		id:null,
	    		precio:0,
	    	};

	    	this.changePrecio = false;

  		},

  		agregarMasFotos(){
  			this.showDialog = true;
  		},


  		closeDialog(){
  			this.precio = 0;
  			this.showDialog = false;
  		},

  		fotoCargada(response,file){
	  		this.updateAlbum(response.album);
	  		this.precio = 0 ;
	  		this.showDialog = false;
	  		// this.pushFoto(response.imagen);
	  	},


	  	cargarImagenes(){
	  		this.$refs.upload.submit();
	  	}

  },

  watch:{

  	id(){
  		this.capturarAlbum(this.id);
  	}

  }



}
</script>

<style lang="css" scoped>
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