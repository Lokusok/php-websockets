<script setup>
import { onMounted, ref, watch } from 'vue';
import { useRouter } from 'vue-router';

import useSessionStorage from '../composables/useSessionStorage';

const props = defineProps({
    ws: Object,
});

const { status, data, send, open, close } = props.ws;
const sessionStorage = useSessionStorage();
const router = useRouter();

if (sessionStorage.value.token) {
    router.replace({ name: 'home' });
}

const username = ref('');
const waitingLogin = ref(false);
const error = ref('');

onMounted(() => {
  if (sessionStorage.value.token) {
    send(JSON.stringify({
      type: 'connect',
      data: {
        username: sessionStorage.value.username,
        token: sessionStorage.value.token,
      }
    }));
  }
});

const callbacks = {
  login() {
    send(JSON.stringify({
      type: 'connect',
      data: {
        username: username.value.trim(),
        token: null,
      }
    }));

    username.value = '';
    waitingLogin.value = true;
  },
};

watch(data, () => {
  const parsedData = JSON.parse(data.value);

  error.value = '';

  waitingLogin.value = false;

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

  <p v-if="waitingLogin">Loading...</p>
  <p v-if="error" style="color: red">{{ error }}</p>

  <form @submit.prevent="callbacks.login">
    <input v-model="username" type="username">
    <br>
    <button :disabled="! username || waitingLogin">Enter chat</button>
  </form>
</template>