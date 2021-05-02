<template>
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">

					
					<div class="card-body">
						<div class="row">
							<div class="col-12">
									<div class="input-group input-group-sm">
										<!-- <div class="input-group-prepend">
											<span class="input-group-text">Búsqueda rápida</span>
										</div> -->

										<!-- <input type="search" v-model="busqueda" class="form-control " placeholder="Busqueda inteligente..." @keyup="buscarTable"> -->
										<p>Aquí están tus artículos comprados. Puedes quitar si lo deseas en la lista en su <router-link :to="{name:'carrito'}">cesta</router-link> antes del pago final.</p>
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

									<div class="col-12 col-md-6 d-flex justify-content-start">
										<el-button type="primary" size="small" circle @click="regresarPosition"><i class="fas fa-chevron-left"></i></el-button>
									</div>

									<div class="col-12 col-md-6 d-flex justify-content-end">
										<strong class="mr-3 my-0 py-0"> Costo total:{{ getTotalCarrito | currency }} USD</strong>

										<!-- <el-popconfirm
											  title="Esta seguro de continuar con la compra?"
											  confirmButtonText="Sí, estoy seguro"
											  cancelButtonText="Nó"
											  confirmButtonType="success"
											  cancelButtonType="danger"
											  @onCancel="noEstoySeguro"
											  @onConfirm="confirmarCompra">
											  
											  <el-button type="primary" size="small" slot="reference" :loading="loading"><i class="fas fa-check-double mr-2"></i>Confirmar</el-button>
											
											</el-popconfirm> -->


											<PayPal :amount="getMontoPagar" currency="USD" :client="credentials" :env="envPaypal" :items="listaCompra" @payment-completed="pagoCompletado"></PayPal>
										

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
	import {mapState, mapGetters, mapActions, mapMutations} from 'vuex';

	import  PayPal from 'vue-paypal-checkout';

	export default{

		components:{
				VdtnetTable,
				PayPal
		},

		props:{
		
		},

		data(){
			return{
				titulo:'Cesta de Compra',

				personal:[],
				costo_total:0,
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
					},

			}
		},

		computed:{
			...mapState(['loading','errors']),
			...mapGetters('usuario',['getFotoInCarrito','getTotalCarrito']),
			...mapGetters('album',['getNombreAlbum']),

			...mapState('configuracion',['configuracion']),

			credentials(){

				if(this.configuracion.production_paypal){
				
					return {
						production: (this.configuracion.id) ? this.configuracion.paypal_id : null,
					};
				
				}else{

					return {
						sandbox:(this.configuracion.id) ? this.configuracion.paypal_id : null
					};
				
				}
				
			},

			envPaypal(){
				return (this.configuracion.production_paypal) ? 'production' : 'sandbox' ;
			},

			getMontoPagar(){
				return String(this.getTotalCarrito);
			},

			listaCompra(){

				return this.getFotoInCarrito.map(foto => {
					return {
						'name'       :`Foto del album: ${this.getNombreAlbum(foto)}`,
						"description":"Foto Profesional",
						"quantity"     :"1",
						"price"      :String(foto.precio),
						"currency"   :"USD"
					};
				})
			}

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

				this.quitarCelCarrito
				
			},

			regresarPosition(){
				this.$eventHub.$emit('regresarPosition');
			},

			confirmarCompra(){
				this.$eventHub.$emit('pagoCompletado');
			},


			noEstoySeguro(){
				
			},

			pagoCompletado(response){
				this.$eventHub.$emit('pagoCompletado',response);
			}


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