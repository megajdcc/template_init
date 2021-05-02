<template>
	<div id="userbox" class="d-flex mr-md-5 flex-nowrap" v-if="usuario" style="position:relative">
      <a href="#" data-toggle="dropdown" class="d-flex justify-content-center align-items-center">

         <figure class="profile-picture img-perfil my-0">
              <img :src="usuario.avatar" :alt="usuario.nombre" class="rounded-circle" style="width:40px; height:40px"/>
         </figure>
                                        
         <div class="profile-info px-2">
        		<span class="name font-weight-bold" v-if="usuario">{{  usuario.nombre }}</span>
         </div>
         <i class="fa custom-caret"></i>
      </a>

		<div class="dropdown-menu p-2" style="position: absolute">
			<ul class="list-unstyled mb-2">
				<li class="divider"></li>
				  	<router-link tag="li" :to="{name:'perfil'}"><a title="Mi Perfil" class="btn btn-sm btn-secondary btn-block"><i class="fas fa-edit"></i>Mi Perfil</a></router-link>
				<li class="divider"></li>
				
				<li class="mt-2">
					<el-button type="danger" size="small" @click="cerrarSesion" class="w-100" plain :loading="loading"><i class="fas fa-sign-in-alt mr-2"></i>Cerrar sesión</el-button>
				</li>
			</ul>
		</div>
	</div>
</template>


<script>
	import { mapState } from 'vuex';

	export default{

			computed:{
				...mapState('usuario',['usuario']),
				...mapState(['loading'])
			},

			methods:{
				cerrarSesion(){				
					this.$store.dispatch('cerrarSesion')
				},

			},

	}

</script>

<style lang="scss" scope>
		
		.dropdown-menu{
			top:40px !important;
		}

	
</style>