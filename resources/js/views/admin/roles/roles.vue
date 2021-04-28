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
										hideFooter
										@editarRol="editarRol"
										@eliminarRol="eliminar"
										className="table-bordered table table-sm table-hover responsive wrap display w-100">

									</vdtnet-table>
									
								</div>

							</div>

						
					</div>


						<div class="card-footer">
								<div class="btn-group">
									 <button type="button" @click="mostrarFormRoles" class="btn btn-outline-primary" size="small">Nuevo Rol</button>
								</div>
						</div>

				</div>
				
			</div>
		</div>
		
		<form-roles :showDialog.sync="showDialog" @cerrar="showDialog = false"></form-roles>
	
	</div>

</template>


<script>
	
	import VdtnetTable  from 'vue-datatables-net';
	import FormRoles from './FormRoles.vue';

	import { mapState, mapGetters, mapMutations, mapActions} from 'vuex'; 

	// import AdministradoresForm from './AdministradoresForm.vue';

	export default{

		components:{
			VdtnetTable,
			FormRoles,
		},

		data(){
			return{

				titulo:'Roles de usuario',
				busqueda:'',
				shownewAdministrador:false,
				rol:null,
				showDialog:false,
				options:{
						ajax:{
							url:'/configuracion/roles/listar/table',
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
						paging:false,
						scrollY:300,
						autoWidth:true,
					},


				data:{
						id     :{label:'#',sortable:false, width:"30px",responsivePriority:"1"},
						name   :{label:'Roles',sortable:true},
						actions:{label:'Actions',sortable:false,width:'150px'},
					},
			}

		},

		computed:{
			...mapState('rol',['roles']),

		},

		methods:{

			...mapMutations('rol',['clearRol','capturarRol','putRol']),
			...mapActions('rol',['eliminarRol']),


			mostrarFormRoles(){

				this.clearRol();
	
				this.showDialog = true;

			},

			buscarTable(){

				this.$refs.table.search(this.busqueda);
			},

			recargarTabla(){

				this.$refs.table.reload();
			},


			editarRol(data){	

				this.capturarRol(data.id);
				this.showDialog   = true;

			},

			eliminar(data){

				this.eliminarRol(data.id).then(respon => { 
						if(respon.data.success){
							this.$notify({
								title:'Rol Eliminado con éxito',
								type:'info'
							});

							this.putRol(data.id);

						}else{
							this.$notify({
								title:'No pudimos eliminar al rol inténtalo de nuevo mas tarde',
								type:'info',

							});

						}

				}).catch(e => {
					console.log(e);
				})

			}	


		},

		watch:{

			roles(){
				this.$refs.table.reload()
			}

		}

	}

</script>