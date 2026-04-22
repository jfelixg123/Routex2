import './bootstrap';
import AppWrapper from './components/AppWrapper.vue';
import 'mapbox-gl/dist/mapbox-gl.css'

import HomeDashboard from './components/componentesHome/HomeDashboard.vue';
import MenuLateralComponent from './components/componentesMenu/MenuLateralComponent.vue';
import LoginComponent from './components/componentesLogin/LoginComponent.vue';
import App from './components/App.vue';
import router from '../js/router/index.js';
import { createApp } from 'vue/dist/vue.esm-bundler.js';


const app = createApp(App);

// Montar el router
app.use(router);

// Para añadir los componentes de nuestra web
app.component('menu-lateral-component', MenuLateralComponent);
app.component('home-dashboard', HomeDashboard);
app.component('app-wrapper', AppWrapper);
app.component('login-component', LoginComponent);



// Montar los componentes en un sitio
app.mount('#offers');
