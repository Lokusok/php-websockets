<script setup>
import { ref, watch } from 'vue';
import { useRouter } from 'vue-router';
import { useWebSocket } from '@vueuse/core';

import wsConfig from '../config/ws';
import useSessionStorage from '../composables/useSessionStorage';

const { status, data, send, open, close } = useWebSocket(wsConfig.url);
const sessionStorage = useSessionStorage();
const router = useRouter();

const username = ref('');
const waiting = ref(false);
const error = ref('');

const callbacks = {
  login() {
    send(JSON.stringify({
      type: 'connect',
      data: {
        username: username.value.trim(),
      }
    }));

    username.value = '';
    waiting.value = true;
  },
};

watch(data, () => {
  const parsedData = JSON.parse(data.value);

  error.value = '';

  waiting.value = false;

  switch (parsedData.type) {
    case 'connect.success': {
        sessionStorage.value.userId = parsedData.data.user_id;
        sessionStorage.value.username = parsedData.data.username;
        sessionStorage.value.token = parsedData.data.token;

        router.replace({ name: 'home' });
        break;
    }

    case 'connect.error': {
      error.value = parsedData.data.message;
      break;
    }
  };
});
</script>

<template>
  <h1>Login</h1>

  <p v-if="waiting">Loading...</p>
  <p v-if="error" style="color: red">{{ error }}</p>

  <form @submit.prevent="callbacks.login">
    <input v-model="username" type="username">
    <br>
    <button :disabled="! username || waiting">Enter chat</button>
  </form>
</template>