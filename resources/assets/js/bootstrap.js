try {
    window._ = require('lodash');

} catch (e) {

}

try {
    window.$ = window.jQuery = require('jquery');
    require('jquery-ui');
    require('jquery-sortable');
    require('bootstrap-sass');


} catch (e) {

}


/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {

}

try {
    window.Pusher = require('pusher-js');
    window.Echo = require('laravel-echo');

    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '4f539992504be074c712',
        cluster: 'eu',
        encrypted: true,
        namespace: 'App.Events'
    });
} catch (e) {

}
