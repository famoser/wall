/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.scss';

const $ = require('jquery');
require('bootstrap');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});


import Vue from 'vue';
import VueI18n from 'vue-i18n';
import { BootstrapVue } from 'bootstrap-vue';
Vue.use(VueI18n);
Vue.use(BootstrapVue);

import { library } from '@fortawesome/fontawesome-svg-core';
import { faEdit, faTrash, faPlus } from '@fortawesome/pro-light-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add(faEdit, faTrash, faPlus);

Vue.component('font-awesome-icon', FontAwesomeIcon);

Vue.config.productionTip = false;
// components

// app
import App from './app.vue';

// messages
const i18n = new VueI18n({
    locale: document.documentElement.lang.substr(0, 2),
    messages: {
        en: require('./translations/app.en.json'),
    }
});

new Vue({
    i18n,
    el: '#app',
    components: { App },
    render (h) {
        return h('App');
    }
});
