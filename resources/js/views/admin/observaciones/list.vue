<template>
		<div class="container-fluid" v-loading="loading" loading-element-spinner="spinner-grow">
		<div class="row">
			<div class="col-12">

				<div class="card">
					<div class="card-header py-1 my-0 d-flex align-items-center text-white justify-content-between" style="background-color: #444444">
						<h3 class="font-weight-bold my-auto">{{ titulo }}</h3>
					
					</div>


					<div class="card-body">
						<div class="row">
							<div class="col-12">
									<div class="input-group input-group-sm">
										<div class="input-group-prepend">
											<span class="input-group-text">Búsqueda rápida</span>
										</div>

										<input type="search" v-model="busqueda" class="form-control " placeholder="Busqueda inteligente..." @keyup="buscarTable">

										<div class="input-group-append">
											<button type="button" @click="recargarTabla" class="btn btn-outline-primary input-group-text"><i class="fas fa-redo"></i></button>
										</div>


									</div>
								</div>

								<div class="col-12 my-2">
									
									<vdtnet-table
										ref="table"
										:fields="data"
										:opts="options"
										@atender="observacionAtendida"
										@mostrar="mostrarObservacion"
										@eliminar="eliminarObservacion"
										className="table-bordered table table-sm table-hover responsive wrap display w-100">

									</vdtnet-table>
									
								</div>

							</div>

						
					</div>
				</div>

				<el-dialog :visible.sync="showDialog" title="Observación" top="15vh" width="400" :before-close="cerrarDialogoObservacion">
					<p v-text="observacion.comentario"></p>
				</el-dialog>				
			</div>
		</div>

	

	</div>
</template>

<script>

import VdtnetTable  from 'vue-datatables-net';


import {mapState, mapGetters, mapMutations, mapActions} from 'vuex';

export default {

  components:{
  	VdtnetTable
  },	

  data () {
    return {
    	titulo:'Observaciones',
    	busqueda:'',
    	showDialog:false,
    	data:{
				id      :{label:'#',sortable:false, width:"30px",responsivePriority:"1"},
				modulo  :{label:'Modulo',sortable:true},
				usuario :{label:'Usuario',sortable:true},
				atendido:{label:'Atendido',sortable:true},
				fecha   :{label:'Fecha',sortable:true},
				action  :{label:'Actions',sortable:false,width:'150px'},
    	}
    }	
  },


  computed:{
	  	...mapState(['loading']),
	  	...mapState('observaciones',['observaciones','observacion']),


	  	options(){
	  		return {
	  			ajax:{
					url:'/observaciones/listar',
					dataScr:(json) => {return json},
				},

				buttons:['csv','pdf'],
				// dom:"tr<'row vdtnet-footer'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7' pl>>",
				responsive:true,
				processing:true,
				searching:true,
				searchDelay:1500,
				destroy:false,
				ordering:true,
				lengthChange:true,
				serverSide:true,
				fixedHeader:true,
				saveState:true,
				scrollY:300,
				paging:false,
				autoWidth:true,
				order:[[0,'desc']]
	  		}
	  	}
  },

  methods:{
  	...mapActions('observaciones',['atendido','delete']),
  	...mapMutations('observaciones',['putObservacion','capturarObservacion','clearObservacion']),


  	buscarTable(){
  		this.$refs.table.search(this.busqueda);
  	},

  	recargarTabla(){
  		this.$refs.table.reload();
  	},

  	observacionAtendida(data){
  		this.atendido(data.id);

  		setTimeout(() => {
  			this.recargarTabla();
  		},3000);
  		this.recargarTabla();
  	},


  	mostrarObservacion(data){
  		this.capturarObservacion(data.id);
  		this.showDialog = true;
  	},

  	eliminarObservacion(data){

  		this.delete(data.id).then(respon => {

  			if(respon.data.success){
  				this.$notify.success('Observación eliminada exitosamente');
  				this.putObservacion(data.id);
  			}else{
				this.$notify.info('La observación no se pudo eliminar, inténtalo de nuevo mas tarde');
  			}

  		}).catch(e => {
  			console.log(e);
  		});
  	},

  	cerrarDialogoObservacion(){
  		this.clearObservacion();
  		this.showDialog = false;
  	}



  },

  watch:{
	 	
	 	observaciones(){
	 		this.$refs.table.reload();
	 	}

  }

}
</script>

<style lang="css" scoped>
</style>