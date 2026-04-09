import './bootstrap';

import HomeDashboard from './components/HomeDashboard.vue';
import MenuLateralComponent from './components/MenuLateralComponent.vue';

import { createApp } from 'vue/dist/vue.esm-bundler.js';
const app = createApp({});

// Para añadir los componentes de nuestra web
app.component('menu-lateral-component', MenuLateralComponent);
app.component('home-dashboard', HomeDashboard);

// Montar los componentes en un sitio
app.mount('#offers');
