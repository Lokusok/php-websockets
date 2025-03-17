import { createRouter, createWebHistory } from 'vue-router';

import Login from './pages/Login.vue';
import Home from './pages/Home.vue';

const routes = [
  { path: '/', component: Home, name: 'home' },
  { path: '/login', component: Login, name: 'login' },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
