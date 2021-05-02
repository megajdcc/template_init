/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;


window.Vue = require('vue');
Window.CustomSlider = require('vue-custom-range-slider');

import Vue from 'vue';

import Element from 'element-ui';
import Axios from 'axios';

import 'element-ui/lib/theme-chalk/index.css';
// import user_mixin from './mixins/user_mixins'
import lang from 'element-ui/lib/locale/lang/es';
import locale from 'element-ui/lib/locale';
import Echo from 'laravel-echo';

// import vuex from 'vuex';

window.Pusher = require('pusher-js');
window.axios = Axios;

locale.use(lang);

Vue.use(Element, {size: 'large'});
Vue.prototype.$eventHub = new Vue();
Vue.prototype.$http = Axios;
Vue.prototype.$echo = Echo;
// Vue.use(vuex);


import TheMask from 'vue-the-mask';

Vue.use(TheMask);





// import {BootstrapVue, IconsPlugin} from 'bootstrap-vue';

// Vue.use(BootstrapVue);
// Vue.use(IconsPlugin); 

// LAYOUTS 
Vue.component('app',require('./components/app.vue').default);

Vue.component('top-header',require('./components/layouts/TopHeader.vue').default); 

Vue.component('notificaciones',require('./components/notificaciones.vue').default);

Vue.component('header-user',require('./components/headeruser.vue').default);


import Donut from 'vue-css-donut-chart';
import 'vue-css-donut-chart/dist/vcdonut.css';
Vue.use(Donut);




import VueCurrencyInput from 'vue-currency-input'
 
Vue.use(VueCurrencyInput)

import VueCurrencyFilter from 'vue-currency-filter';



Vue.use(VueCurrencyFilter, {
		symbol:"$",
		thousandsSeparator:',',
		fractionCount:'2',
		fractionSeparator:'.',
		symbolPosition:'front',
		symbolSpacing:true
});


import numeral from 'numeral';
import numFormat from 'vue-filter-number-format';

Vue.filter('numFormat', numFormat(numeral));



import VueSvgGauge from 'vue-svg-gauge';

Vue.use(VueSvgGauge);


import VueGauge from 'vue-gauge';

Vue.component('vue-gauge',VueGauge);



// Bootstrap-vue

import {BootstrapVue, IconsPlugin} from 'bootstrap-vue';
Vue.use(BootstrapVue);
Vue.use(IconsPlugin); 



window.clone = function(obj){
	return JSON.parse(JSON.stringify(obj));
}

window.random = (min, max) => {
	return Math.round(Math.random() * (max - min) + min);
}

window.redondeo = function(num, decimales = 2){
	var signo = (num >= 0 ? 1 : -1);
	num  = num * signo;

	if(decimales === 0)
			return signo * Math.round(num);

	num = num.toString().split('e');

	num = Math.round(+(num[0] + 'e' + (num[1] ? (+num[1] + decimales)  : decimales)));
	num = num.toString().split('e');
	return signo * (num[0] + 'e'+(num[1] ? (+num[1] - decimales) : -decimales));
} 


window.colorRand = () => {
	let colores = [
		'#397DAD',
		'#4D616C',
		'#D02412',
		'#CD960E',
		'#348022',
		'#17B6AA',
		'#5F2626',
		'#00AEFF',
		'#6574cd',
		'#9561e2',
		'#f66d9b',
		'#e3342f',
		'#f6993f',
		'#ffed4a',
		'#38c172',
		'#4dc0b5',
		'#6cb2eb',
		'#444444',
		'#00c0ef',
	];


	var i = random(0,colores.length);


	return colores[i];

}



// const pond = FilePond.create({
// 	files:[
// 		{
// 			source:'GETATTACHMENT',

// 			options
// 		}
// 	]
// });


import router from './router/index.js';
import store from './store/index.js';


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


const app = new Vue({
  	el:'#app',
  	router,
  	store,
});

const t = '';

