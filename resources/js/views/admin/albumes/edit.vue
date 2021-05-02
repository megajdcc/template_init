<script>
	import {mapState,mapActions ,mapMutations} from 'vuex';
	import form from './form.vue';

	export default {

		props:['id'],

		render:(createElement) => {
			
			return createElement(form,{
				props:{
					titulo:'Editar Album',
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

		computed:{
			...mapState(['loading','errors']),

		},

		mounted(){
			this.capturarAlbum(this.id);
		},

		methods:{
			...mapMutations('album',['capturarAlbum']),
			
		},


		watch:{

			id(){
				this.capturarAlbum(this.id);
			}

		}
		



	}
	
</script>