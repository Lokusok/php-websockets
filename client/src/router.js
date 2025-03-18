import { createRouter, createWebHistory } from 'vue-router';

import Login from './pages/Login.vue';
import Home from './pages/Home.vue';
import Room from './pages/Room.vue';

const routes = [
  { path: '/', component: Home, name: 'home' },
  { path: '/login', component: Login, name: 'login' },
  { path: '/room/:id', component: Room, name: 'room' },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
