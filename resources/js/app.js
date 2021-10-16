import Vue from 'vue';
import Vuex from 'vuex';
import VueRouter from 'vue-router';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';
import Vuelidate from 'vuelidate';
import VuelidateErrorExtractor, { templates } from 'vuelidate-error-extractor';
import VueMoment from 'vue-moment';

import { validation, attributes, validationKeys } from './lang/en/validation';

Vue.use(Vuex);
Vue.use(VueRouter);
Vue.use(Vuetify);
Vue.use(Vuelidate);
Vue.use(VuelidateErrorExtractor, {
    messages: validation,
    validationKeys : validationKeys,
    attributes: attributes,
});
Vue.use(VueMoment);

import store from './store';
import router from './router';
import httpService from './services/http';
import mixins from './helpers/mixins';

import AppIndex from 'base/pages/AppIndex';
import FormGroup from 'base/components/ui/validations/FormGroup';
import FormSummary from 'base/components/ui/validations/FormSummary';

Vue.mixin(mixins);
Vue.component('AppIndex', AppIndex);
Vue.component('FormGroup', FormGroup);
Vue.component('FormSummary', FormSummary);
Vue.component('FormWrapper', templates.FormWrapper);

/**
 * Initialize websockets
 */
import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: false,
    wsHost: window.location.hostname,
    wsPort: 6001,
 });

/**
 * Initialize HTTP service
 */
 httpService.initialize('/api/');
 httpService.setHeader();
 httpService.mountErrorInterceptor();
 
 const app = new Vue({
     store,
     router,
     vuetify: new Vuetify(),
     el: '#app',
 });