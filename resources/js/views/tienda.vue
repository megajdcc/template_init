<template>
<div class="container-fluid">
		<div class="row">
				<div class="col-12">
					<el-tabs type="border-card">
						<el-tab-pane label="Fotos">

								<div class="contain-pr d-flex flex-wrap py-3 justify-content-center" v-loading="loading">

									<div class="card-categoria d-flex flex-column  align-items-center m-3" v-for="(foto,i) in getFotosAlbumes" :key="i" style="cursor:pointer">
										<article class="img-categoria elevation-2 rounded-lg" style="width:160px; height:105px" >
											<img :src="foto.foto_con_marca"  class="rounded-lg w-100 h-auto" :alt="foto.foto_con_marca" @click.stop="index = i">
										</article>
										
										<span class="font-weight-bold text-center mt-2" style="font-size:10pt">Album: {{ getNombreAlbum(foto) }}</span>
										
										<div class="btn-group btn-group-sm">

											<button type="button" class="btn btn-outline-primary" @click="anadirCarrito(foto)"><i class="fas fa-shopping-cart mr-2"></i>Añadir</button>

										</div>

											<span class="font-weight-bold text-center mt-2" style="font-size:10pt">Precio: {{ foto.precio  | currency }} USD</span>

									</div>
									
								</div>

						</el-tab-pane>		


					</el-tabs>	
				</div>
			</div>
			<vue-gallery-slideshow :images="getFotosGallery" :index="index" @close="index = null"></vue-gallery-slideshow>
	</div>
</template>

<script>

import {mapState, mapGetters,mapActions, mapMutations} from 'vuex';
import VueGallerySlideshow from 'vue-gallery-slideshow';
export default {

	components:{
		VueGallerySlideshow
	},
  
  data(){
    return {
    	busqueda:'',
    	index:null,
    }
  },


  computed:{
  	...mapState(['loading','errors']),
  	...mapState('usuario',['usuario']),
  	...mapState('album',['albumes']),
  	...mapGetters('album',['getAlbumes','getFotosAlbumes','getNombreAlbum']),


  	getFotosGallery(){
  		return this.getFotosAlbumes.map(foto => {
  			return {url:foto.foto_con_marca,alt:'Foto'};
  		})
  	}

  },


  methods:{
  		...mapActions('usuario',['agregarAlCarrito']),

  	anadirCarrito(foto){
  		this.agregarAlCarrito(foto).then(result => {
  			this.$notify.success({
  				title:'Foto agregada al carrito de compra exitosamente.',
  				position:'bottom-left',
  			});
  		});
  	},

  }


}
</script>

<style lang="css" scoped>

.img-categoria img{
	height:105px !important;
	object-fit:contain;
}


</style>