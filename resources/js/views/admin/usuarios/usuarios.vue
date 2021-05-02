<template>
	<div class="container-fluid">
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
										hideFooter
										@editarUsuario="editarUsuario"
										@eliminarUsuario="eliminarUsuario"
									
										className="table-bordered table table-sm table-hover responsive wrap display w-100">

									</vdtnet-table>
									
								</div>

							</div>

						
					</div>


						<div class="card-footer">
								<div class="btn-group">
									 <button type="button" @click="mostrarFormUsuario" class="btn btn-outline-primary" size="small">Nuevo Usuario</button>
								</div>
						</div>

				</div>
				
			</div>
		</div>
		<form-usuario :showDialog.sync="showDialog" :title="tituloDialogo" @recargar-usuario="recargarTabla" @cerrarModal="showDialog = false"></form-usuario>
	</div>

</template>

<script>
	
	import VdtnetTable  from 'vue-datatables-net';
	import FormUsuario from './FormUsuario.vue';

	// import AdministradoresForm from './AdministradoresForm.vue';
	import {mapState, mapGetters, mapActions, mapMutations } from 'vuex';

	export default{

		components:{
			VdtnetTable,
			FormUsuario,
		},

		data(){
			return{

				titulo:'Todos los Usuarios',
				busqueda:'',
				shownewAdministrador:false,
				showDialog:false,
				tituloDialogo:'Crear Usuario',
				options:{
						ajax:{
							url:'/listar/usuarios',
							dataScr:(json) => {return json},
						},
						buttons:['csv','pdf'],
						// dom:"tr<'row vdtnet-footer'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7' pl>>",
						responsive:false,
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
						colReorder:{
							enable:false
						},

						select:false,
						selectCheckbox:0,
					},

				data:{
						id    :{label:'#',sortable:false, width:"30px",responsivePriority:"1"},
						name  :{label:'Nombre',sortable:true},
						rol   :{label:'Rol',sortable:true},
						creado:{label:'Creado',sortable:true},
						action:{label:'Actions',sortable:false,width:'150px'},
					},
			}

		},

		computed:{
			...mapState('usuario',['usuario','usuarios'])
		},


		methods:{
			...mapMutations('usuario',['capturarUsuario','clearUsuario']),

			mostrarFormUsuario(){

				this.clearUsuario();
				this.showDialog = true;

			},

			buscarTable(){

				this.$refs.table.search(this.busqueda);
			},

			recargarTabla(){

				this.$refs.table.reload();
			},


			editarUsuario(data){
					this.tituloDialogo = 'Editar Usuario';
				

					this.capturarUsuario(data.id);
					this.showDialog   = true;
			},

			eliminarUsuario(data){

				axios.delete('/usuarios/'+data.id).then(respon => {

						if(respon.data.success){
							this.$notify({
								title:'Usuario Eliminado con éxito',
								type:'info'
							});

							this.$refs.table.reload();

						}else{
							this.$message.danger('No pudimos eliminar el usuario inténtalo de nuevo mas tarde');
						}

				}).catch(e => {
					console.log(e);
				})
			},

			


		},


		watch:{
			usuarios(){
				// this.$refs.table.reload();
			}
		}

	}

</script>