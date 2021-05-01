import Vue from 'vue';
import Vuex from 'vuex';
import VueRouter from 'vue-router';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';
import Vuelidate from 'vuelidate';
import VuelidateErrorExtractor, { templates } from 'vuelidate-error-extractor';

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

import store from './store';
import router from './router';
import httpService from './services/http';
import mixins from './helpers/mixins';

import AppIndex from './core/base/pages/AppIndex';
import FormGroup from './core/base/components/ui/validations/FormGroup';
import FormSummary from './core/base/components/ui/validations/FormSummary';

Vue.mixin(mixins);
Vue.component('AppIndex', AppIndex);
Vue.component('FormGroup', FormGroup);
Vue.component('FormSummary', FormSummary);
Vue.component('FormWrapper', templates.FormWrapper);

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