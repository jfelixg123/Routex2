import { createRouter, createWebHashHistory  } from 'vue-router';

import LoginComponent from '../components/componentesLogin/LoginComponent.vue';
import AppWrapper from '../components/AppWrapper.vue';

const routes = [
  {
    path: '/',
    component: LoginComponent
  },
  {
    path: '/dashboard',
    component: AppWrapper
  }
];

const router = createRouter({
    history: createWebHashHistory(),
    routes
});

export default router;
