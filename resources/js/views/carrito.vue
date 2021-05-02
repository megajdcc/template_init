<template>
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">

				<div class="card">
					<div class="card-header py-1 my-0 d-flex align-items-center text-white justify-content-between" style="background-color: #444444">
						<h3 class="font-weight-bold my-auto">{{ titulo }}</h3>
						<!-- <div class="btn-group btn-table">
							<button type="button" class="btn btn-outline-primary" @click="showPersonal"><i class="fas fa-user-plus mr-2"></i>Nuevo Empleado</button>
						</div> -->
					</div>


					<div class="card-body">
						<div class="row">
							<div class="col-12">
									<div class="input-group input-group-sm">
										<!-- <div class="input-group-prepend">
											<span class="input-group-text">Búsqueda rápida</span>
										</div> -->

										<!-- <input type="search" v-model="busqueda" class="form-control " placeholder="Busqueda inteligente..." @keyup="buscarTable"> -->

									</div>
								</div>

								<div class="col-12 my-2">
									
									<vdtnet-table
										ref="table"
										:fields="data"
										:opts="options"
										hideFooter
										className="table table-reflow table-sm responsive nowrap w-100"
										@quitarItem="quitarItem"
										>
									</vdtnet-table>
									
								</div>

							</div>

						
					</div>


						<div class="card-footer">
							<div class="container-fluid">
								<div class="row">
									<div class="col-12 col-md-6">
										<strong> Costo total:{{ getTotalCarrito | currency }} USD</strong>
									</div>
									
									<div class="col-12 col-md-6">
											<div class="btn-group">
												 <button type="button" @click="pasarCaja()" class="btn btn-outline-primary" size="small">Pasar por la caja<i class="fas fa-check-double ml-1"></i></button>
											</div>
									</div>

								</div>
							</div>
							
						</div>




				</div>
				
			</div>
		</div>

	</div>
</template>


<script>

	import VdtnetTable  from 'vue-datatables-net';	
	import {mapState,mapGetters, mapMutations, mapActions} from 'vuex';

	export default{

		components:{
			VdtnetTable,
		},

		data(){
			return{
				titulo:'Cesta de Compra',
				busqueda:'',
				options:{
						ajax:{
							url:'/tienda/cargar/carrito',
							dataScr:(json) => {return json},
						},
						buttons:['csv','pdf'],
						// dom:"tr<'row vdtnet-footer'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7' pl>>",
						responsive  :true,
						processing  :true,
						searching   :true,
						searchDelay :1500,
						destroy     :false,
						ordering    :true,
						lengthChange:true,
						serverSide  :true,
						fixedHeader :true,
						saveState   :true,

					},

				data:{
						imagen         :{label:'',sortable:false, width:"150px",responsivePriority:"1"},
						album          :{label:'Álbum',width:'auto',sortable:true, className:'my-auto'},
						precio_unitario:{label:"Precio unitario",sortable:true,width:'70px'},
						cantidad       :{label:'Cantidad',sortable:true,width:'130px'},
						total          :{label:'Total ($)',sortable:true,width:'100px'},
						actions        :{label:'Actions',sortable:false,width:'40px'}					
					},

			}
		},


		computed:{
			...mapState(['errors','loading']),
			...mapGetters('usuario',['getFotoInCarrito','getTotalCarrito']),
		},

		methods:{

			...mapActions('usuario',['quitarCelCarrito']),

			recargarDatos(){
				this.$refs.table.reload();
			},

			
			buscarTable(){
				this.$refs.table.search(this.busqueda);
			},

			quitarItem(data){
				this.quitarCelCarrito(data);
			},

			pasarCaja(){

					this.$router.push({name:'cajatienda'});

					// location.href = '/tienda/caja';
					
			},

	

		},

		watch:{
				getFotoInCarrito(){
					this.$refs.table.reload();
					
				}
		}



	}

</script>

<style>

tr td{
	vertical-align: middle !important;
}

</style>