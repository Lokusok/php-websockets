<script setup>
import { ref, watch } from 'vue';
import { useRouter } from 'vue-router';
import { useWebSocket, useStorage } from '@vueuse/core';

const { status, data, send, open, close } = useWebSocket('ws://127.0.0.1:9502');
const sessionStorage = useStorage('session', { username: null, token: null });
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

  console.log(parsedData);

  waiting.value = false;

  switch (parsedData.type) {
    case 'connect.success': {
        sessionStorage.value.username = parsedData.data.username;
        sessionStorage.value.token = parsedData.data.token;

        router.replace({ name: 'chat' });
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