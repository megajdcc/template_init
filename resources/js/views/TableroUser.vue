<template>

<div class="container-fluid" v-loading="loading" >
	<div class="row">
		<div class="col-12 d-flex justify-content-between mb-1">
			<h4 class="font-weight-bold text-black-50">Tablero</h4>
			<el-button type="primary" size="medium" @click="cargarTablero" icon="el-icon-refresh-right" circle></el-button>
		</div>
	</div>

	<div class="row">

				<div class="col-lg-3 col-xs-6">
	          <!-- small box -->
	          <div class="small-box bg-success">
	            <div class="inner">
	              <h3 class="text-white">{{ total_compras }}</h3>

	              <p class="text-white font-weight-bold">Total Compras</p>
	            </div>
	            <div class="icon">
	              <i class="fas fa-shopping-bag"></i>
	            </div>
					
	          </div>
	        	</div>

					 <!-- ./col -->
		      <div class="col-lg-3 col-xs-6">
		          <!-- small box -->
		          <div class="small-box bg-yellow">
		            <div class="inner">
		              <h3 class="text-white">{{ fotos_compradas }}</h3>
		              <p class="text-white font-weight-bold">Fotos compradas</p>
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
			              <h3 class="text-white font-weight-bold">{{ cantidad_albumes }}</h3>

			              <p>Cantidad de Álbumes </p>
			            </div>
			            <div class="icon">
			              <i class="fas fa-folder-open"></i>
			            </div>
			           
			          </div>
			   </div>

	</div>
</div>

</template>

<script>
import {mapState, mapGetters, mapActions} from 'vuex';
export default {
  data () {
    return {
		
    }
  },

  computed:{
  	...mapState(['errors','loading']),
  	...mapState('user-tablero',['total_compras','fotos_compradas','cantidad_albumes'])

  },

  mounted(){
  	this.cargarTablero();

  	this.$eventHub.$on('recargarTablero',() => {
  		this.cargarTablero();
  	})
  },

  methods:{
  	...mapActions('user-tablero',['cargarTablero'])
  }

  
}
</script>

<style lang="css" scoped>
</style>