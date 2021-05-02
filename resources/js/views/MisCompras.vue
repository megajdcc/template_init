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
                                        @reenviar="reenviarCompra"
                                        className="table-bordered table table-sm table-hover responsive wrap display w-100">
                                    </vdtnet-table>
                                    
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
import {mapState, mapMutations, mapActions} from 'vuex';

export default {
	
	components:{
		VdtnetTable
	},

  	data(){
    return {
    		titulo:'Mis compras',
    		busqueda:'',
    		

          data:{
                  id      :{label:'#',sortable:false, width:"30px",responsivePriority:"1"},
                  monto   :{label:'Monto',sortable:true},
                  fotos   :{label:'Cantidad fotos',sortable:true},
                   fecha   :{label:'fecha de la compra',sortable:true},
                  action :{label:'Actions',sortable:false,width:'150px'},
              },


    }
  	},


  	computed:{
  		...mapState(['loading']),
  		...mapState('usuario',['usuario']),

  		options(){

  			return {
				 ajax:{
                url:(this.usuario.id) ? `/compras/usuario/${this.usuario.id}/listar/compras` : '',
                dataScr:(json) => {return json},
            },
            
            responsive:true,
            processing:true,
            searching:true,
            searchDelay:1500,
            destroy:false,
            ordering:true,
            lengthChange:true,
            serverSide:true,
            fixedHeader:false,
            saveState:true,
            scrollY:300,
            paging:true,
            select:false,
            autoWidth:true,
            order:[[0,'desc']],
  			};
           
     },

  	},

  	methods:{
  		buscarTable(){
  			this.$refs.table.search(this.busqueda);
  		},

  		recargarTabla(){
  			this.$refs.table.reload();
  		},

  		reenviarCompra(data){
			
			axios.get(`/compras/${data.id}/reenviar/email`).then(respon => {

				if(respon.data.success){
					this.$notify.success('Se le ha reenviado un email con las fotos de esta compra')
				}

			}).catch(e => {
				console.log(e);
			})

  		}

  	},


  	watch:{
  		usuario(){
  			this.$refs.table.dataTable.ajax.url(`/compras/usuario/${this.usuario.id}/listar/compras`).load();
  		}
  	}

}
</script>

<style lang="css" scoped>
</style>