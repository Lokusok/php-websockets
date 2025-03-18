<script setup>
import { useWebSocket } from '@vueuse/core';
import useSessionStorage from '../composables/useSessionStorage';
import wsConfig from '../config/ws';

const sessionStorage = useSessionStorage();

const ws = useWebSocket(wsConfig.url);
</script>

<template>
  <div v-if="sessionStorage.token">
      <h3>Login as: {{ sessionStorage.username }}</h3>
  </div>

  <hr>

  <router-view v-slot="{ Component }">
    <component
      :is="Component"
      :ws="ws"
    />
  </router-view>
</template>