<template>
<div class="container-fluid">

		<div class="row mb-3">
			<div class="col-12">
				<div class="btn-group btn-group-sm">
					<router-link :to="{name:'observaciones'}" class="btn btn-outline-dark" class-active="active" exact><i class="fas fa-list-ol mr-2"></i>Observaciones</router-link>

				</div>
			</div>	
		</div>

		<div class="row">
			<router-view></router-view>
		</div>

</div>
	
</template>

<script>
	
	

	export default{

		


		data(){

			return{

			}
		},

	}

</script>


<style lang="scss" scope>


</style>