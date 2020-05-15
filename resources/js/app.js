require('./bootstrap');

// Load Vue
window.Vue = require('vue');

// Load Vuetify framework
import Vuetify from 'vuetify'
Vue.use(Vuetify)

// Load (Vue) Axios
import axios from 'axios'
import VueAxios from 'vue-axios'
Vue.use(VueAxios, axios);

// Load lodash
import _ from 'lodash';

// Load air datepicker
global.datepicker = require('air-datepicker');

// Automatically load all vue components
const files = require.context('./', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

// Create the Vue.js application
const app = new Vue({
    el: '#app',
    vuetify: new Vuetify({
        icons: {
            iconfont: 'mdiSvg', // 'mdi' || 'mdiSvg' || 'md' || 'fa' || 'fa4'
        },
    }),
});