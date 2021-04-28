<template>

	<div class="" v-loading="loading" element-loading-spinner="spinner-grow">
			<h2>Este es el home de PhotoStore</h2>
	</div>


</template>

<script>
	import {mapState} from 'vuex';

	export default{
	
		computed:{
			...mapState(['loading']),
			...mapState('usuario',['usuario']),
		}
	
	}

</script>