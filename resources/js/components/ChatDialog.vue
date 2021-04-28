<template>
	<el-dialog :visible.sync="showChat" :close-on-click-modal="true" :modal="false" with="450px" top="5vh" :before-close="cerrar" custom-class="dialogo-chat" @open="ingresar">
			<slot name="title" v-if="chat.id">
				<ul class="container-user-header d-flex">
					<li  class="list-group-item elevation-1 rounded-circle" v-for="usuario in getUsuarios()" :key="usuario.id">

              <el-tooltip :content="usuario.nombre+' '+usuario.apellido" placement="top">
  							<el-avatar size="small" :src="getAvatar(usuario)" shape="circle"></el-avatar>
              </el-tooltip>

					</li>
				</ul>
			
			</slot>



      <div class=" d-flex flex-column mt-4">
        <div class="row w-100">
          <div class="col-12">
              <div class="block content-chat" v-loading="loading" style="max-height: 60vh; overflow-y: auto">
                <el-timeline>

                  <el-timeline-item v-for="mensaje in chat.mensajes" :key="mensaje.id" :label="mensaje.created_at" :value="mensaje.id" :timestamp="getFecha(mensaje.created_at)" placement="top" v-if="mensajesHoy(mensaje)">
                    <el-card>
                      <div class="d-flex align-items-center mb-2">
                        <el-avatar shape="circle" size="medium" :src="getAvatar(mensaje.usuario)" class="mr-2">
                        </el-avatar>
                        <el-divider content-position="left"><h5>{{ mensaje.usuario.nombre+' '+mensaje.usuario.apellido }}</h5></el-divider>
                      </div>
                        <p style="overflow-wrap:normal; font-style: italic">{{ mensaje.mensaje }}</p>
                    </el-card>
                  </el-timeline-item> 
                </el-timeline>


                <template v-if="chat.mensajes">
                   <strong v-if="chat.mensajes.length < 1">Sin mensajes todavía, entre tu compañero y usted!</strong>
                </template>
               
              </div>
          </div>
        </div>


        <div class="row w-100">
          <div class="col-12">
            <div class="form-group mt-3 elevation-1">
              <div class="input-group d-flex">
                

                <textarea class="form-control" rows="2" placeholder="Ingrese su mensaje..." v-model="formulario.mensaje" style="max-height: 150px"></textarea>
                <div class="input-group-append">
                  <button type="button" @click="enviar" class="btn btn-outline-primary input-group-text d-flex flex-column justify-content-center" :disabled="!formulario.mensaje"><i class="fas fa-paper-plane" style="font-size:18pt"></i></button>
                </div>
              </div>

          </div>
          </div>
        </div>
      </div>

	</el-dialog>
</template>

<script>

import {mapState,mapGetters, mapMutations, mapActions} from 'vuex';

export default {

  name: 'ChatDialog',
  props:['showChat'],

  data () {
    return {
      formulario:{
          mensaje:'',

        }
    } 
  },

  computed:{
    ...mapState(['loading','errors']),
  	...mapState('usuario',['usuario']),
  	...mapState('chat',['chat','redaccion']),

  	usuarios(){
  		return this.chat.usuarios.filter((usuario) => usuario.id != this.usuario.id);
  	},

    mensajes(){
      return this.chat.mensajes;
    },
    redaccion_mensaje(){
      return this.redaccion.mensaje
    }

  },	

  methods:{
  	...mapMutations('chat',['cerrarChat','unirme']),
    ...mapActions('chat',['enviarMensaje']),



  	getUsuarios(){
  		return this.chat.usuarios;
  	},

  	getAvatar(user){
  		return (user.imagen) ? '/storage/img-perfil/'+user.imagen : '/storage/img-perfil/avatar_masculino.jpg';
  	},

  	cerrar(){
  		this.cerrarChat();
      this.$eventHub.$emit('chatOpenUpdate',false);
  		this.$emit('cerrarChat');
  	},

    getFecha(fecha){
      return moment(fecha).format('DD-MM-YYYY HH:mm');
    },


    ingresar(){

      this.unirme(this.usuario.id)
      this.formulario = clone(this.redaccion);

      setTimeout(() => {
            $('.content-chat').animate({scrollTop: $('.content-chat').prop("scrollHeight")}, 500);
      },1000);

    },



    enviar(){

      this.formulario.chat_id = this.chat.id;
      this.formulario.usuario_id = this.usuario.id;

      this.enviarMensaje(this.formulario).then(result => {
         this.formulario.mensaje = ''
      })
     


    },

    mensajesHoy(mensaje){
      return (moment(mensaje.created_at).format('YYYY-MM-DD') == moment().format('YYYY-MM-DD'));
    }
    
  },


   watch:{

      mensajes(){
        
         $('.content-chat').animate({scrollTop: $('.content-chat').prop("scrollHeight")}, 500);

      },

      redaccion_mensaje(){
        this.formulario = clone(this.redaccion);
      }

  }



}
</script>

<style lang="scss" scoped>

.dialogo-chat{
  
  z-index: 700 !important;

  .el-dialog__header{
    display:none !important;
  }

  .el-dialog__body{
    padding: 10px 20px 20px 20px;


    .container-user-header{
      padding:0px;
      margin: 0px;


        li{
          padding:0px;
          margin:0px;
          height:30px;
          border:none;
        }
    }
  }
}
</style>