<script setup>
import useSessionStorage from '../composables/useSessionStorage';

const sessionStorage = useSessionStorage();
</script>

<template>
  <div v-if="sessionStorage.token">
      <h3>Login as: {{ sessionStorage.username }}</h3>
  </div>

  <hr>

  <router-view></router-view>
</template>