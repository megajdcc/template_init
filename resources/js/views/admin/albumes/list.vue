<template>
	<div class="container">
		<div class="row">
			<div class="col-12">

				<div class="card">

					<div class="card-header py-1 my-0 d-flex align-items-center text-white justify-content-between" style="background-color: #444444">
						
						<h3 class="font-weight-bold my-auto">{{ titulo }}</h3>
					
					</div>


					<div class="card-body container-fluid">
							
						<div class="content-folder w-100 h-auto d-flex flex-wrap" v-loading="loading" loading-element-spinner="spinner-grow">
							
							<div class="folder m-1 elevation-1 d-flex justify-content-between" v-for="(album, i ) in albumes" :key="i" @click.stop="openAlbum(album)" :title="album.nombre" >
								<div>
									<span class="fas fa-folder"></span>
									<!-- <el-input v-model="carpeta.nombre" size="mini"></el-input> -->
									<strong class="ml-2">{{ album.nombre.substring(0,15) }}</strong>
								</div>

									<el-button-group>
											<el-button type="primary" size="mini" class="btn-mini" @click.stop="renombrandoAlbum(album)"><i class="fas fa-edit"></i></el-button>
										<el-button type="danger" @click.stop="eliminarAlbum(album)" size="mini" class="btn-mini"><i class="fas fa-trash"></i></el-button>
									</el-button-group>

							</div>
						</div>

					</div>

				</div>
			</div>
		</div>

		<el-dialog title="Renombrar Album" width="30%"
		:visible="renombrando" @close="cerrardialogoRenombrar" :show-close="false">
			<div class="form-group">
				<label for="">Renombrar</label>
				<div class="input-group input-group-sm">
					<input type="text" v-model="album.nombre" class="form-control" :class="{'is-invalid' :errors.nombre}" placeholder="Name Álbum...">

					<div class="invalid-feedback" role="alert" v-if="errors.nombre">
						<strong v-text="errors.nombre[0]"></strong>
					</div>
				</div>
			</div>
			<el-button type="primary" @click="renombrar" size="small" icon="fas fa-save mr-2 mt-2" :loading="loading" :disabled="album.nombre.length < 3">Guardar</el-button>
		</el-dialog>

	</div>
</template>

<script>

import {mapState, mapGetters,mapActions, mapMutations} from 'vuex';

export default {

  data () {
    return {
    	titulo:'Álbumes',
    	renombrando:false,

    	album:{
    		id:null,
			nombre:'',
 			usuario_id:null,
 			publicante:null,
 			fotos:[],
    	}
    }
  },


  computed:{
		...mapState(['loading','errors']),
		...mapState('album',['albumes']),
		...mapGetters('album',['draft']),



  },


  methods:{
  	...mapMutations('album',['capturarAlbum','clearAlbum']),
  	...mapActions('album',['renombrarAlbum','eliminar']),


  	openAlbum(album){

  		this.$router.push({name:'albumes.show.fotos',params:{id:album.id}})
  	},

  	renombrandoAlbum(album){

  		this.capturarAlbum(album.id);
  		this.album = this.draft;
  		this.renombrando = true;

  	},

  	renombrar(){
  		this.renombrarAlbum(this.album);
  		this.renombrando = false
  	},


  	eliminarAlbum(album){
  		this.eliminar(album.id);
  	},

  	cerrardialogoRenombrar(){
  		this.clearAlbum();

  		this.renombrando = false;
  	}

  }

}
</script>

<style lang="scss" scoped>

.btn-mini{
	width:20px;
	height:20px;
	padding:1px 2px;
	background:transparent;
	color:black;
	border:none;
}

.img-pequena{
	width:60px;
	height:60px;
}

.folder{
	padding: .2rem .5rem;
	min-width:200px;
	width:200px;
	cursor:pointer;
}
.folder:hover{
	background:#00aeff;
	color:white;
}


.icon-table{
	font-size:20pt;
}
.el-upload{
	width:100%;

}

.el-upload-dragger{
	width:100%;
	
}

.el-icon-document{
	height:auto !important;
}



</style>