<template>
<div class="container-fluid" v-loading="loading" >
	<div class="row">
		<div class="col-12 d-flex justify-content-between mb-1">
			<h4 class="font-weight-bold text-black-50">Tablero</h4>
			<el-button type="primary" size="medium" @click="cargarTablero" icon="el-icon-refresh-right" circle></el-button>
		</div>
	</div>

	<el-divider></el-divider>
	<div class="row">

				<div class="col-lg-3 col-xs-6">
	          <!-- small box -->
	          <div class="small-box bg-success">
	            <div class="inner">
	              <h3 class="text-white">{{ total_ventas | currency }} USD</h3>

	              <p class="text-white font-weight-bold">Total en ventas</p>
	            </div>
	            <div class="icon">
	              <i class="fas fa-chart-line"></i>
	            </div>
					
	          </div>
	        	</div>

					 <!-- ./col -->
		      <div class="col-lg-3 col-xs-6">
		          <!-- small box -->
		          <div class="small-box bg-yellow">
		            <div class="inner">
		              <h3 class="text-white">{{ cantidad_fotos }}</h3>
		              <p class="text-white font-weight-bold">Cantidad de Fotos</p>
		            </div>
		            <div class="icon">
		              <i class="fas fa-images"></i>
		            </div>
					
						
		          </div>
		      </div>

			   
			   <div class="col-lg-3 col-xs-6">
			          <!-- small box -->
			          <div class="small-box bg-secondary">
			            <div class="inner">
			              <h3 class="text-white font-weight-bold">{{ cantidad_albums }}</h3>

			              <p>Cantidad de Álbumes </p>
			            </div>
			            <div class="icon">
			              <i class="fas fa-folder-open"></i>
			            </div>
			           
			          </div>
			   </div>

		      <div class="col-lg-3 col-xs-6">
			          <!-- small box -->
			          <div class="small-box bg-info">
			            <div class="inner">
			              <h3 class="text-white font-weight-bold">{{ cantidad_usuarios }}</h3>

			              <p>Cantidad de Usuarios </p>
			            </div>
			            <div class="icon">
			              <i class="fas fa-users"></i>
			            </div>
			           
			          </div>
			   </div>


	</div>
</div>


</template>

<script>

import { mapState, mapGetters, mapActions } from 'vuex';

export default {

  data () {
    
    return {
   
    }


  },


  computed:{
  	...mapState(['errors','loading']),
  	...mapState('admin-tablero',['total_ventas','cantidad_albums','cantidad_usuarios','cantidad_fotos'])
  },


  mounted(){
  	this.cargarTablero();

  	this.$eventHub.$on('recargarTablero',() => {
  		this.cargarTablero();
  	})
  },

  methods:{
  	...mapActions('admin-tablero',['cargarTablero'])
  }


}
</script>

<style lang="css" scoped>
</style>