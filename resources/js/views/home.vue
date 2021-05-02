<template>
	<div>
		<tablero-admin v-if="isAdmin"></tablero-admin>
		<tablero-user v-else></tablero-user>
	</div>
</template>

<script>

	import { mapState,mapGetters } from 'vuex';
	import TableroAdmin from './TableroAdmin.vue';
	import TableroUser from './TableroUser.vue';

	export default{
		
		components:{
			TableroAdmin,
			TableroUser
		},

		computed:{
			...mapState(['loading']),
			...mapState('usuario',['usuario']),


			isAdmin(){
				if(this.usuario.id){
					return (this.usuario.rol.name == "Super Administrador");
				}
			}
		}
	
	}

</script>