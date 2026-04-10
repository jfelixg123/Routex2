import './bootstrap';
import AppWrapper from './components/AppWrapper.vue';

import HomeDashboard from './components/componentesHome/HomeDashboard.vue';
import MenuLateralComponent from './components/componentesMenu/MenuLateralComponent.vue';

import { createApp } from 'vue/dist/vue.esm-bundler.js';
const app = createApp({});

// Para añadir los componentes de nuestra web
app.component('menu-lateral-component', MenuLateralComponent);
app.component('home-dashboard', HomeDashboard);
app.component('app-wrapper', AppWrapper);

// Montar los componentes en un sitio
app.mount('#offers');
