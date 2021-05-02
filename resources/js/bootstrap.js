window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');
    require('overlayscrollbars');
   require('../../vendor/almasaeed2010/adminlte/dist/js/adminlte');

    require('bootstrap');

 	window.pdfMake = require('pdfmake/build/pdfmake.js');
	window.pdfFonts = require('pdfmake/build/vfs_fonts.js');
	pdfMake.vfs = pdfFonts.pdfMake.vfs;
	
	window.JSZip = require('jszip');
	require('datatables.net-buttons-bs4');
	require('datatables.net-buttons/js/buttons.flash.js');  
	require('datatables.net-buttons/js/buttons.html5.js');  
	require('datatables.net-buttons/js/buttons.print.js');
	require('datatables.net-buttons/js/dataTables.buttons.min.js');  
	require('datatables.net-responsive-bs4');


	var tether = require('tether');

		
} catch (e) {}





try{
	
	window.moment = require('moment');
	moment.locale('es');
	require('chart.js');

}catch(e){

}


window.Swal = require('sweetalert2');



/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    window.headers_token = {
        'X-CSRF-TOKEN': token.content,
    };
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}


/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */


// import Echo from 'laravel-echo';
// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
// 	broadcaster      : 'pusher',
// 	key              : 'cd833ae469c0e52b244a',
// 	cluster          : 'us2',
// 	disabledStats    : true,
// 	wsPort           : 6002,
// 	forceTLS         : false,
// 	wsHost           : 'sk.jitsi.app',
// 	encrypted        : true,
// });
