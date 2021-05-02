<script>
	import {mapState,mapActions ,mapMutations} from 'vuex';
	import form from './form.vue';

	export default {

		render:function(createElement){
			
			return createElement(form,{
				props:{
					titulo:'Crear Álbum',
				},

				on:{
					
					regresar(){
						window.history.length > 0 ? this.$router.go(-1) : this.$router.push('/home');
					},

					save:(data) => {

					}

				}

			});

		},


		data(){
			return {

			}

		},

		mounted(){
			this.clearAlbum();

		},
		
		activated(){
			this.clearAlbum();
		},

		computed:{
			...mapState(['loading','errors']),

		},


		methods:{
			...mapMutations('album',['clearAlbum'])
		}




	}
	
</script>