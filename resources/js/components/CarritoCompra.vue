<template>

<div>
		<el-badge :value="countFotos" class="item mx-2" v-if="countFotos > 0">
			<button @click="drawer = true"  type="button" class="btn btn-outline-dark btn-sm border-0" title="Mi Carrito de Compra" data-toggle="tooltip">
				<i class="fas fa-shopping-cart" style="font-size: 14pt"></i>
			</button>
		</el-badge>
	
	

	<el-drawer title="Mi Carrito de compra" :visible.sync="drawer" custom-class="content-notificaciones" :modal="false" :direction="direccion" :before-close="close" size="320px">
					
					<div class="container d-flex flex-column " style="overflow-y: auto; height: 80vh; padding: 0 1rem 1rem 0; text-align:justify">
						<el-timeline :reverse="reverse" class="pl-2 w-100" v-loading="loading">
						<el-timeline-item 
							v-for="(foto,index) in getFotoInCarrito"
							:key="index"
							:timestamp="getFecha(foto.pivot.created_at)"
							icon="fas fa-shopping-bag text-black"
							placement="top"
							type="dark"
							color="#4AC5E0"
							size="large">	

								<el-card style="cursor:pointer" shadow="hover">
									<div class="container">
										<div class="row w-100">
											<div class="col-6">

												<ul class="p-0">
													<li style="list-style: none"><span class="" style="font-size:10pt !important; font-weight: 500">Precio:{{ foto.precio  | currency }} USD</span></li>
												</ul>
											
												
											
											</div>
											<div class="col-5">
												<img :src="foto.foto_con_marca" :alt="foto.foto_con_marca" class="rounded-lg elevation-1" style="width: 100%; height:auto;object-fit:contain">
											</div>
											
											<div class="col-1">
												<el-button type="danger" circle size="mini" native-type="button" icon="fas fa-trash" @click="eliminarDelCarrito(foto)"></el-button>
											</div>

										</div>
									</div>
									
									

									<!-- <button type="button" title="Ver notificación" data-toggl="tooltip" class="btn btn-light btn-block" @click="mostrar(notification)"><i class="fas fa-eye" style="font-size: 16pt"></i></button> -->
								</el-card>

						</el-timeline-item>
						</el-timeline>


						
					</div>

					<el-button-group><el-button type="primary" native-type="button" @click="irCesta" class="d-block mx-2" v-if="countFotos > 0">Ir a la cesta</el-button></el-button-group>


	</el-drawer>

</div>
	

</template>


<script>
	
import {mapState,mapGetters,mapActions,mapMutations } from 'vuex';

export default {

			data(){
				return {
					drawer:false,
					direccion:'rtl',
					reverse:true,
					resource:'/carrito/compra',
					productos:[],	
					showCarrito:false,
				
					dnotification:{
						id:0,
						data:{
							titulo:'',
							mensaje:'',
							btn:false,
							btnTitle:'',
							url:''
						},

					},



				}

			},
			
			created(){

			},
			

			computed:{
				...mapState(['loading','errors']),
				...mapGetters('usuario',['getFotoInCarrito']),

				countFotos:function(){
						return (this.getFotoInCarrito) ? this.getFotoInCarrito.length : 0;
				},

			},

			methods:{
				...mapActions('usuario',['quitarCelCarrito']),

				// showNotificacion(){
				// 	this.showNotify= true
				// },
				close(done){

					done();

				},
				
				cerrar(){
					this.showCarrito = false
				},

				getFecha(fecha){
					return moment(fecha).format('LLL')
				},

				irCesta(){

					this.$router.push({name:'carrito'});

				},



				eliminarDelCarrito(foto){
					this.quitarCelCarrito(foto);
				}


			}

	}

</script>

<style>
	
	.el-card__body{
		
		padding:5px !important;
	}

</style>