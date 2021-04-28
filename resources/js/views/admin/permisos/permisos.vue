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
                                        @editarPermiso="editarPermiso"
                                        @eliminarPermiso="eliminar"
                                        className="table-bordered table table-sm table-hover responsive wrap display w-100">

                                    </vdtnet-table>
                                    
                                </div>

                            </div>

                        
                    </div>


                        <div class="card-footer">
                                <div class="btn-group">
                                     <button type="button" @click="mostrarFormPermisos" class="btn btn-outline-primary" size="small">Nuevo Permiso</button>
                                </div>
                        </div>

                </div>
                
            </div>
        </div>
        <form-permiso :showDialog.sync="showDialog" @cerrar="showDialog = false"></form-permiso>
    </div>

</template>


<script>
    
    import VdtnetTable  from 'vue-datatables-net';
    import FormPermiso from './FormPermiso.vue';


    import {mapState, mapMutations, mapActions} from 'vuex';

    // import AdministradoresForm from './AdministradoresForm.vue';

    export default{

        components:{
            VdtnetTable,
            FormPermiso,
        },

        data(){
            return{

                titulo:'Permisos de usuario',
                busqueda:'',
                shownewAdministrador:false,
            
                showDialog:false,
                options:{

                        ajax:{
                            url:'/configuracion/listar/permisos',
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
                    },

                data:{
                        id      :{label:'#',sortable:false, width:"30px",responsivePriority:"1"},
                        name   :{label:'Permisos',sortable:true},
                        action :{label:'Actions',sortable:false,width:'150px'},
                    },
            }

        },

        computed:{
                ...mapState(['errors','loading']),
                ...mapState('permiso',['permisos','permiso']),
        },

        methods:{

            ...mapMutations('permiso',['clearPermiso','capturarPermiso','putPermiso']),
            ...mapActions('permiso',['eliminarPermiso']),
            mostrarFormPermisos(){

                this.clearPermiso();

                this.showDialog = true;

            },

            buscarTable(){

                this.$refs.table.search(this.busqueda);
            },

            recargarTabla(){

                this.$refs.table.reload();
            },


            editarPermiso(data){

            

                    this.capturarPermiso(data.id);
                    
                    this.showDialog   = true;
            },

            eliminar(data){

                this.eliminarPermiso(data.id).then(respon => {

                        if(respon.data.success){
                            this.$notify({
                                title:'Permiso Eliminado con éxito',
                                type:'info'
                            });

                            this.putPermiso(data.id);

                        }else{
                            this.$message.danger('No pudimos eliminar el permiso inténtalo de nuevo mas tarde');
                        }

                }).catch(e => {
                    console.log(e);
                })
            }   


        },


        watch:{
            permisos(){
                this.$refs.table.reload(false);
            },
        }

    }

</script>