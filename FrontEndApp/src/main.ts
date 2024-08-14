import { createApp } from 'vue'
import { createPinia } from 'pinia'
import * as Vue from 'vue' 
import axios from 'axios'
import VueAxios from 'vue-axios'

import App from './App.vue'
import router from './router'

import './assets/main.css'

// Import Bootstrap CSS and optionally Bootstrap-Vue-3 CSS
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue-3/dist/bootstrap-vue-3.css';

import BootstrapVue3 from 'bootstrap-vue-3';

const app = createApp(App)
app.use(VueAxios, axios)
app.use(createPinia())
app.use(BootstrapVue3)
app.use(router)

app.mount('#app')
